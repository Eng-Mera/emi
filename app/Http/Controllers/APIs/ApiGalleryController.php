<?php

namespace App\Http\Controllers\APIs;

use App\File;
use App\FileMeta;
use App\Gallery;
use App\Http\Controllers\Controller;
use App\ImagesSocial;
use App\MenuItem;
use App\Restaurant;
use App\Role;
use App\User;

use App\Http\Requests;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Config;

use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Tests\File\FakeFile;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Restaurant Gallery.
 *
 * @Resource("Restaurants Gallery", uri="/api/v1/restaurant/{restaurant_slug}/gallery")
 */
class ApiGalleryController extends Controller
{

    use Helpers;

    /**
     * List Gallery Items
     *
     * @Get("{?per_page,search,order,order_type}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="integer", required=false, description="The short name of restaurant.", default=10),
     * })
     */
    public function index(Requests\GalleryRequest $request, $restaurantSlug)
    {

        $perPage = Request::get('per_page', 10);
        $orderBy = Request::get('order', 'id') ? Request::get('order', 'id') : 'id';
        $orderDir = Request::get('order_type', 'desc') ? Request::get('order_type', 'desc') : 'desc';

        $restaurant = Restaurant::with('gallery')->whereSlug($restaurantSlug)->firstOrFail();

        if (!$restaurant->gallery) {
            $this->response->errorNotFound('No gallery created yet');
        }

        $galleryId = $restaurant->gallery->id;

        if ($perPage <= 0) {
            $files = File::with('meta')->objectType(Gallery::class)->where('imageable_id', $galleryId)->orderBy($orderBy, $orderDir)->get();
        } else {
            $files = File::with('meta')->objectType(Gallery::class)->where('imageable_id', $galleryId)->orderBy($orderBy, $orderDir)->paginate($perPage);
        }

        $gallery = $restaurant->gallery;

        $gallery->files = $files;

        return $this->response->created('', $gallery);
    }

    /**
     * Create Gallery
     *
     * @Post("/")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="integer", required=false, description="The short name of restaurant.", default=10),
     *      @Parameter("name", type="string", required=true, description="The gallery name."),
     *      @Parameter("slug", type="string", required=true, description="The url friendly name.", default=""),
     *      @Parameter("images[]", type="array", required=true, description="The gallery images as base64encode format", default=""),
     *      @Parameter("description", type="string", required=true, description="The gallery description.", default=""),
     * })
     */
    public function store(Requests\GalleryRequest $request, $restSlug)
    {

        $user = User::getCurrentUser(true);

        $restaurant = Restaurant::with('gallery', 'gallery.file')->whereSlug($restSlug)->first();

        $inputs = $request->only(Requests\GalleryRequest::getFields());

        $inputs = Requests\GalleryRequest::removeParent($inputs);

        \DB::beginTransaction();

        if ($restaurant->gallery) {
            //Create Gallery
            $gallery = $restaurant->gallery;

        } else {

            $inputs['restaurant_id'] = $restaurant->id;
            $inputs['user_id'] = $user->id;
            $inputs['slug'] = !empty($inputs['slug']) ? $inputs['slug'] : $restaurant->slug . '-' . $restaurant->id;

            $gallery = Gallery::create($inputs);
        }

        //Store Images
        $this->storeImages($request, $gallery, $user);

        \DB::commit();

        $gallery = Gallery::with(['file', 'file.meta'])->find($gallery->id);

        if ($request->get('for_mobile', false)) {

            $gallery = $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->get(env('API_VERSION', 'v1') . '/restaurant/' . $restSlug . '/gallery');
        }

        return $this->response->created('', $gallery);
    }

    protected function storeImages($request, $gallery, $user)
    {

        if (!empty($request->get('images')) || !empty($request->file('images'))) {
            foreach ($request->get('images') as $img) {

                $imgData = json_decode($img, 1);
                if (is_null($imgData)) {
                    throw new BadRequestHttpException('Image details format are wrong!');
                }

                foreach ($imgData as $imgD) {
                    //Update Logo
                    $file = $this->api
                        ->version(env('API_VERSION', 'v1'))
                        ->header('webAuthKey', Config::get('api . webAuthKey'))
                        ->post(env('API_VERSION', 'v1') . '/file', [
                            'uploaded_file' => $imgD['source'],
                            'internal_user_id' => $user->id
                        ]);

                    if ($file) {

                        $file->category = File::getCategorySlug('restaurant_gallery');
                        $gallery->file()->save($file);

                        $fileMeta = new FileMeta();
                        $fileMeta->title = $imgD['name'];
                        $fileMeta->description = $imgD['description'];

                        $file->meta()->save($fileMeta);
                    }
                }
            }
        }
    }

    protected function destroyImages($inputs, $gallery, $user)
    {
        if (!empty($inputs) || !empty($inputs)) {

            foreach (explode(',', $inputs) as $img) {

                $fileOwnerId = File::find($img)->user_id;

//                if ($fileOwnerId == $user->id) {
                $this->api
                    ->version(env('API_VERSION', 'v1'))
                    ->header('webAuthKey', Config::get('api . webAuthKey'))
                    ->delete(env('API_VERSION', 'v1') . '/file/' . $img);
//                }
            }

        }
    }

    /**
     * Read Gallery Files
     *
     * @Get("/{image_id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="string", required=true, description="The restaurant slug."),
     *      @Parameter("image_id", type="string", required=true, description="The id of image. of gallery"),
     * })
     */
    public function show(Requests\GalleryRequest $request, $restaurantSlug, $id)
    {
        $gallery = File::with(['meta', 'comments' => function ($q) {
            $q->limit(5)->orderBy('id', 'DESC');
        }])->whereId($id)->firstOrFail();

        return $this->response->created('', $gallery);
    }

    /**
     * Update likes, dislikes and share
     *
     * @Get("/social-media/{image_id}/{action}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="string", required=true, description="The restaurant slug."),
     *      @Parameter("image_id", type="string", required=true, description="The id of image. of gallery"),
     *      @Parameter("action", type="string", required=true, description="The type of action [likes, dislikes, shares]"),
     * })
     */
    public function social(Requests\GalleryRequest $request, $restauarnt_slug, $image_id, $action)
    {

        if (!in_array($action, ['likes', 'dislikes', 'shares'])) {
            return $this->response->errorBadRequest('Action must be in the following values [likes, dislikes, shares]');
        }

        $file = File::findOrFail($image_id);

        $currentUser = User::getCurrentUser();

        $socialMedia = ImagesSocial::where([
            'user_id' => $currentUser->id,
            'file_id' => $image_id
        ])->first();


        $arr = ['likes', 'dislikes'];
        unset($arr[array_search($action, $arr)]);
        $other = array_values($arr)[0];

        if ($socialMedia && $socialMedia->$action == 1 && in_array($action, ['likes', 'dislikes'])) {

            $actionString = [
                'likes' => 'liked',
                'dislikes' => 'disliked',
                'shares' => 'shared'
            ];

            return $this->response->error('You already ' . $actionString[$action] . ' this image', '400');
        }

        $socialMedia = ImagesSocial::where([
            'user_id' => $currentUser->id,
            'file_id' => $image_id,
        ])->first();

        if ($socialMedia && $socialMedia->count()) {

            if (in_array($action, ['likes', 'dislikes'])) {

                $socialMedia->{$action} = 1;
                $socialMedia->{$other} = 0;

            } else {
                $socialMedia->{$action} = 1;
            }

            $socialMedia->save();

        } else {

            $socialMediaData = [
                'user_id' => $currentUser->id,
                'file_id' => $image_id,
                $action => 1
            ];

            ImagesSocial::create($socialMediaData);
        }

        $fileMeta = FileMeta::whereFileId($image_id)->firstOrFail();
        if (in_array($action, ['likes', 'dislikes'])) {
            $fileMeta->increment($action);
            if ($fileMeta->$other > 0) {
                $fileMeta->decrement($other);
            }
        } else {
            $fileMeta->increment($action);
        }

        $file = File::with(['meta'])->whereId($image_id)->firstOrFail();

        return $this->response->created('', $file);
    }

    /**
     * Update Gallery
     *
     * @Put("/{review_id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="string", required=true, description="The restaurant slug."),
     *      @Parameter("name", type="string", required=true, description="The gallery name."),
     *      @Parameter("slug", type="string", required=true, description="The url friendly name.", default=""),
     *      @Parameter("images[]", type="array", required=true, description="The gallery images as base64encode format", default=""),
     *      @Parameter("description", type="string", required=true, description="The gallery description.", default=""),
     * })
     */
    public function update(Requests\GalleryRequest $request, $restaurantSlug, $slug)
    {
        $user = User::getCurrentUser();

        $gallery = Gallery::with(['file'])->whereSlug($slug)->firstOrFail();

        //Get Inputs
        if ($user->isOwner($gallery) || $user->hasRole(Role::SUPER_ADMIN)) {

            $inputs = $request->only(['name', 'slug', 'description']);

            $restaurant = Restaurant::whereSlug($restaurantSlug)->firstOrFail();
            $inputs['restaurant_id'] = $restaurant->id;

            $inputs = array_filter($inputs);

            //Update Restaurant
            $gallery->fill($inputs)->save();
        }

        //Store Images
        $this->storeImages($request, $gallery, $user);


        if ($user->isOwner($gallery) || $user->hasRole(Role::SUPER_ADMIN)) {
            //Get Inputs
            $inputs = $request->get('destroy_images');

            $this->destroyImages($inputs, $gallery, $user);
        }

        $gallery = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/gallery');


        return $this->response->created('', $gallery);
    }

    /**
     * Delete Gallery
     *
     * @Delete("/{gallery_slug}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="string", required=true, description="The restaurant slug."),
     *      @Parameter("gallery_slug", type="string", required=true, description="The ulr friendly name of gallery."),
     * })s
     */
    public function destroy(Requests\GalleryRequest $request, $restaurantSlug, $slug)

    {
        $restaurant = Restaurant::whereSlug($restaurantSlug)->firstOrFail();

        $gallery = Gallery::with('file')->where(['slug' => $slug, 'restaurant_id' => $restaurant->id])->firstOrFail();

        $request = new \Illuminate\Http\Request();

        $user = User::getCurrentUser();

        $ids = [];

        foreach ($gallery->file as $file) {
            $ids[] = $file->id;
        }

        $ids = implode(',', $ids);

        $this->destroyImages($ids, $gallery, $user);

        $gallery = $gallery->delete();

        if (!$gallery) {
            return $this->response->errorNotFound(trans('Gallery not found!'));
        }

        return $this->response->created('', 1);
    }

}
