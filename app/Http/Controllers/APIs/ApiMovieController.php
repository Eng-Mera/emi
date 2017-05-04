<?php

namespace App\Http\Controllers\APIs;

use App\File;
use App\Http\Helpers\FileTrait;
use App\Movie;
use App\Http\Controllers\Controller;
use App\User;

use App\Http\Requests;
use Dingo\Api\Routing\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

/**
 * Movies.
 *
 * @Resource("Movies", uri="/api/v1/movie")
 */
class ApiMovieController extends Controller
{

    use Helpers, FileTrait;

    /**
     * List Movies
     *
     * @Get("/{?per_page,search,order,order_type}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("per_page", type="integer", required=false, description="Number of items per pages.", default=10),
     *      @Parameter("search", type="string", required=false, description="A search query.", default=""),
     *      @Parameter("order", type="string", required=false, description="Column name to order results by.[id, created_at, rate_value, user_rating]", default="id"),
     *      @Parameter("order_type", type="string", required=false, description="Type of sorting (DESC - ASC).", default="DESC"),
     * })
     */
    public function index(Requests\MovieRequest $request)
    {
        $client = new \GuzzleHttp\Client();
        $req = $client->request('GET','https://passappengine.e7gezly.com/api/HTREvents/all.json',[
            'headers' => [
                'Authorization' => 'ENGINE token=wrc32js8rdc3478nr237dhfyu3gb76b34&apikey=lhkiwyegr',
            ]
        ]);

        $response = json_decode($req->getBody(),true);
        $categories = [];
        if (!empty($response) and isset($response['data']['info']) and !empty($response['data']['info']))
        {
            $movies = $response['data']['info'];
            $categories = [];
            foreach ($movies as $movie)
            {
                $row = Movie::first();
                $row->id = $movie['id'];
                $row->name = $movie['title'];
                $row->description = $movie['description'];
                $row->booking_url = $movie['booking_url'];
                $row->enable_booking = 1;
                $row->poster = (object) [
                    'image_url' => $movie['image'],
                    'id' => $movie['id'],
                    'filename' => '',
                    'mime' => '',
                    'original_filename' => '',
                    'user_id' => '',
                    'imageable_id' => 1,
                    'imageable_type' => 'App\\Movie',
                    'category' => 'movie_poster',
                    'created_at' => '',
                    'updated_at' => '',
                    'number_of_comments' => 0,


                ];
                $categories['data'][] = $row;
            }
        }

        $perPage = Request::get('per_page', 10);

        if ($perPage == -1) {
            $movies = [];

            $movies['movies'] = $categories['data'];

            return $this->response->created('', $movies);
        }

        $categories['data'] = (!isset($categories['data']))? [] : $categories['data'];
        $collection = new Collection($categories['data']);
        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 5;
        $currentPageResults = $collection->slice(($page * $perPage) - $perPage , $perPage)->all();

        $paginatedResults = new LengthAwarePaginator($currentPageResults , count($collection) , $perPage , $page , ['path' => Paginator::resolveCurrentPath() ] );

        return $paginatedResults->toArray();

        /*
         * Our Old API
         */

//        $perPage = Request::get('per_page', 10);
//
//        $searchParams = [
//            Request::get('search', false) ? Request::get('search', false) : false,
//        ];
//
//        if (array_filter($searchParams)) {
//            $searchQuery = implode(' ', $searchParams);
//        } else {
//            $searchQuery = false;
//        }
//
//        $orderBy = Request::get('order', 'id') ? Request::get('order', 'id') : 'id';
//        $orderDir = Request::get('order_type', 'desc') ? Request::get('order_type', 'desc') : 'desc';
//
//        $with = ['poster'];
//
//        if ($perPage == -1) {
//            $categories = Movie::with($with)->orderBy($orderBy, $orderDir)->get();
//        } else {
//
//
//            if ($searchQuery) {
//                $categories = Movie::with($with)->search($searchQuery)->orderBy($orderBy, $orderDir)->paginate($perPage);
//            } else {
//                $categories = Movie::with($with)->orderBy($orderBy, $orderDir)->paginate($perPage);
//            }
//        }

//        return $this->response->created('', $categories);

    }

    public function featureMovies()
    {
        $with = ['poster'];
        $movies = Movie::with($with)->Where(['add_to_featured' => '1'])->orderBy('created_at', 'desc')->take(10)->get();
        return $this->response->created('', $movies);
    }

    /**
     * Create Movie
     *
     * @Post("/")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("name", type="string", required=true, description="The movie name.", default=""),
     *      @Parameter("description", type="string", required=true, description="The movie description.", default=""),
     *      @Parameter("poster", type="base64", required=true, description="The movie poster in base63 format.", default=""),
     *      @Parameter("booking_url", type="string", required=true, description="The widget from cinemawy.com url.", default=""),
     *      @Parameter("enable_booking", type="boolean", required=true, description="a flag to enable or disable booking.", default=""),
     * })
     */
    public function store(Requests\MovieRequest $request)
    {
        //Get Inputs
        $inputs = $request->only(Requests\MovieRequest::getFields());
        $inputs['user_id'] = User::getCurrentUser()->id;
        $poster = $request->get('poster', $request->file('poster', false));
        $movieFeaturedImage = $request->get('movie_featured_image', $request->file('movie_featured_image', false));

        \DB::beginTransaction();

        //Create Menu Item
        $movie = Movie::create($inputs);

        //Save Poster
        $this->storeMovieImages($poster, $movie, File::getCategorySlug('movie_poster'), 'poster');

        //Save Movie Featured Image
        $this->storeMovieImages($movieFeaturedImage, $movie, File::getCategorySlug('movie_featured_image'), 'featuredImage');

        \DB::commit();

        $movie = Movie::with(['poster'])->find($movie->id);

        return $this->response->created('', $movie);
    }

    /**
     * Read Movie
     *
     * @Get("/{id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="integer", required=true, description="The movie id.", default=""),
     * })
     */
    public function show($id)
    {
        $movie = Movie::with('poster', 'featuredImage')->findOrFail($id);
        return $this->response->created('', $movie);
    }

    /**
     * Update Movie
     *
     * @Put("/{id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="integer", required=true, description="The movie id.", default=""),
     *      @Parameter("name", type="string", required=true, description="The movie name.", default=""),
     *      @Parameter("description", type="string", required=true, description="The movie description.", default=""),
     *      @Parameter("poster", type="base64", required=true, description="The movie poster in base64 format.", default=""),
     *      @Parameter("booking_url", type="string", required=true, description="The widget from cinemawy.com url.", default=""),
     *      @Parameter("enable_booking", type="boolean", required=true, description="a flag to enable or disable booking.", default=""),
     *      @Parameter("add_to_featured", type="string", required=true, description="A flag to enable adding featured image to slider.", default=""),
     *      @Parameter("movie_featured_image", type="boolean", required=true, description="The featured image in base64 format.", default=""),
     * })
     */
    public function update(Requests\MovieRequest $request, $id)
    {
        $movie = Movie::whereId($id)->firstOrFail();

        //Get Inputs
        $inputs = $request->only(Requests\MovieRequest::getFields());
        $poster = $request->get('poster', $request->file('poster', false));
        $movieFeaturedImage = $request->get('movie_featured_image', $request->file('movie_featured_image', false));

        //Get Movie
        $movie = Movie::with('poster')->find($movie->id);

        \DB::beginTransaction();

        //Update Restaurant
        $movie->fill($inputs)->save();

        //Update Poster
        $this->storeMovieImages($poster, $movie, File::getCategorySlug('movie_poster'), 'poster');

        //Update Featured image
        $this->storeMovieImages($movieFeaturedImage, $movie, File::getCategorySlug('movie_featured_image'), 'featuredImage');

        \DB::commit();

        return $this->response->created('', $movie);
    }

    /**
     * Delete Movie
     *
     * @Delete("/{id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="integer", required=true, description="The movie id.", default=""),
     * })
     */
    public function destroy($id)
    {
        $movie = Movie::where(['id' => $id])->firstOrFail();

        $movie->poster()->delete();
        $movie->delete();

        return $this->response->created('', $movie);
    }

}
