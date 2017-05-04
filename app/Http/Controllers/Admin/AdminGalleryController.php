<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 5/9/16
 * Time: 11:31 AM
 */

namespace App\Http\Controllers\Admin;


use App\FileMeta;
use App\Restaurant;
use App\User;
use App\Lang;

use Illuminate\Support\Facades\File;

use Dilab\Network\SimpleRequest;
use Dilab\Network\SimpleResponse;
use Dilab\Resumable;

use App\Gallery;
use App\Http\Controllers\APIs\ApiGalleryController;
use App\Http\Requests\GalleryRequest;
use Dingo\Api\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Mockery\CountValidator\Exception;

class AdminGalleryController extends ApiGalleryController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GalleryRequest $request, $restaurantSlug)
    {
        $res = Restaurant::whereSlug($restaurantSlug)->firstOrFail();

        $this->authorize('listGallery', $res);

        try {

            $gallery = $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->get(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/gallery');

        } catch (\Exception $e) {
            $gallery = new Gallery();
        }

        return view('admin.gallery.index')->with(['restaurant_slug' => $restaurantSlug, 'gallery' => $gallery]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(GalleryRequest $request, $restaurantSlug)
    {
        return Redirect::route('admin.restaurant.gallery.index', [$restaurantSlug]);

//        $res = Restaurant::whereSlug($restaurantSlug)->firstOrFail();
//
//        $this->authorize('listGallery', $res);
//
//        $gallery = new Gallery();
//
//        $locales = Lang::all();
//
//        return view('admin.gallery.create')->with(['locales' => $locales, 'gallery' => $gallery, 'restaurant_slug' => $restaurantSlug]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Requests\RestaurantRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(GalleryRequest $request, $restSlug)
    {
        $res = Restaurant::whereSlug($restSlug)->firstOrFail();

        $this->authorize('listGallery', $res);

        $menuItem = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->attach($request->allFiles())
            ->post(env('API_VERSION', 'v1') . '/restaurant/' . $restSlug . '/gallery', $inputs);

        return Redirect::route('admin.restaurant.gallery.edit', [$restSlug, $menuItem->slug])->with('content-message', trans('Gallery has been created Successfully'));
    }

    public function resumableUpload()
    {

        $tmpPath = storage_path() . '/tmp';
        $uploadPath = storage_path() . '/uploads';

        if (!File::exists($tmpPath)) {
            File::makeDirectory($tmpPath, $mode = 0777, true, true);
        }

        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, $mode = 0777, true, true);
        }

        $request = new SimpleRequest();
        $response = new SimpleResponse();

        $resumable = new Resumable($request, $response);
        $resumable->tempFolder = $tmpPath;
        $resumable->uploadFolder = $uploadPath;

        try {
            $result = $resumable->process();
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            die;
        }

        $file = $resumable->resumableParams()['resumableFilename'];

        if (file_exists($uploadPath . '/' . $file)) {

            $restarauntSlug = Request::get('restaurant_slug');

            $restaurant = Restaurant::with('gallery')->whereSlug($restarauntSlug)->first();

            $gallery = $restaurant->gallery;

            if (!$gallery) {
                $data = [
                    'user_id' => User::getCurrentUser()->id,
                    'name' => $restaurant->name,
                    'slug' => '',
                ];
                $gallery = $restaurant->gallery()->create($data);
            }

            //Update Logo
            $uploadedFile = $this->api
                ->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->post(env('API_VERSION', 'v1') . '/file', [
                    'uploaded_file' => $this->imagetoBase64($uploadPath . '/' . $file),
                    'internal_user_id' => \Auth::user()->id
                ]);

            if ($uploadedFile) {

                $uploadedFile->category = \App\File::getCategorySlug('restaurant_gallery');
                $gallery->file()->save($uploadedFile);

                $fileMeta = new FileMeta();
                $fileMeta->title = $file;
                $fileMeta->description = $file;

                $uploadedFile->meta()->save($fileMeta);

                $result = 200;

                unlink($uploadPath . '/' . $file);
            }

        }

        switch ($result) {
            case 200:
                return response([
                    'message' => 'OK',
                    'file' => $uploadedFile->toArray()
                ], 200);
                break;
            case 201:
                // mark uploaded file as complete etc
                return response([
                    'message' => 'OK',
                ], 200);
                break;
            case 204:
                return response([
                    'message' => 'Chunk not found',
                ], 204);
                break;
            default:
                return response([
                    'message' => 'An error occurred',
                ], 404);
        }
    }

    protected function imagetoBase64($path)
    {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);

        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function show(GalleryRequest $request, $restaurantSlug, $id)
    {

        $galleryItem = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/gallery/' . $id);

        $galleryItem->votes_count = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/file/' . $galleryItem->id . '/vote/?vote_count=1');

        return view('admin.gallery.show')->with(['galleryItem' => $galleryItem]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function edit(GalleryRequest $request, $restaurantSlug, $slug)
    {
        $locales = Lang::all();

        $menuItem = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/menu-item/' . $slug);

        return view('admin.gallery.edit')->with(['locales' => $locales, 'menuitem' => $menuItem, 'restaurant_slug' => $restaurantSlug]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Requests\RestaurantRequest $request
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function update(GalleryRequest $request, $restaurantSlug, $slug)
    {
        $inputs = $request->all();

        if (!isset($inputs['popular_dish'])) {
            $inputs['popular_dish'] = 0;
        }

        $menuItem = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->attach($request->allFiles())
            ->post(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/menu-item/' . $slug, $inputs);

        return Redirect::route('admin.restaurant.menu-item.edit', [$restaurantSlug, $menuItem->slug])->with('content-message', trans('Gallery has been updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy(GalleryRequest $request, $restaurantSlug, $id)
    {
        $inputs = [
            'destroy_images' => $id,
            '_method' => 'PATCH'
        ];

        $slug = Restaurant::with('gallery')->whereSlug($restaurantSlug)->firstOrFail();

        if (!$slug->gallery) {
            $msg = 'Something went wrong please try again later!';
            return Redirect::route('admin.restaurant.gallery.index', $restaurantSlug)->with('error-message', trans($msg));
        }

        $slug = $slug->gallery->slug;

        $gallery = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->post(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/gallery/' . $slug, $inputs);

        if ($gallery) {
            $msg = 'Gallery has been deleted successfully!';
            return Redirect::route('admin.restaurant.gallery.index', $restaurantSlug)->with('content-message', trans($msg));
        } else {
            $msg = 'Gallery has already been deleted!';
            return Redirect::route('admin.restaurant.gallery.index', $restaurantSlug)->with('error-message', trans($msg));
        }

    }
}