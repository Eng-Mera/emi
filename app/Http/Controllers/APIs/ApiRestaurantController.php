<?php

namespace App\Http\Controllers\APIs;

use App\Exceptions\PushNotificationException;
use App\Http\Helpers\RestaurantTrait;
use App\Role;
use Event;
use App\Http\Controllers\Controller;
use App\Http\Helpers\FileTrait;
use App\Http\Helpers\OpeningDayTrait;
use App\OpeningDay;
use App\Restaurant;
use App\User;
use App\Events\RequestReview;
use App\Http\Requests;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Config;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Restaurant Representation.
 *
 * @Resource("Restaurants", uri="/api/v1/restaurant")
 */
class ApiRestaurantController extends Controller
{
    use Helpers, FileTrait, RestaurantTrait;

    /**
     * List all restaurants
     *
     * @Get("/{?per_page,search,order,id,order_type,filters[price_from],filters[price_to],filters[distance][value],filters[distance][latitude],filters[distance][longitude],filters[category],filters[rating],filters[popularity],filters[type],filters[htr_stars]}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("per_page", type="integer", required=false, description="Number of items per pages.", default=10),
     *      @Parameter("search", type="string", required=false, description="A search query.", default=""),
     *      @Parameter("order", type="string", required=false, description="Column name to order results by [id, name, created_at,updated_at].", default="id"),
     *      @Parameter("order_type", type="string", required=false, description="Type of sorting (Descending - Ascending).", default="desc"),
     *      @Parameter("filters[price_from]", type="integer", required=false, description="Filter results by range of price.", default=""),
     *      @Parameter("filters[price_to]", type="integer", required=false, description="Filter results by range of price.", default=""),
     *      @Parameter("filters[distance][value]", type="integer", required=false, description="Filter results by distance.", default=""),
     *      @Parameter("filters[distance][latitude]", type="integer", required=false, description="Filter results by latitude.", default=""),
     *      @Parameter("filters[distance][longitude]", type="integer", required=false, description="Filter results by longitude.", default=""),
     *      @Parameter("filters[category]", type="integer", required=false, description="Filter results by category.", default=""),
     *      @Parameter("filters[rating]", type="integer", required=false, description="Filter results by rate value.", default=""),
     *      @Parameter("filters[rating_stars]", type="integer", required=false, description="Filter results by rate value [1,2,3,4,5].", default=""),
     *      @Parameter("filters[popularity]", type="integer", required=false, description="Filter results by popularity.", default=""),
     *      @Parameter("filters[city_id]", type="integer", required=false, description="Filter results by city.", default=""),
     *      @Parameter("filters[in_out_door]", type="integer", required=true, description="The Restaurant type in door or outdoor or both.
    Type                    | Key
    ------------------------|----
    In Door                 | 1
    Out Door                | 2
    Both                    | 3
    ", default=""),
     *      @Parameter("filters[type]", type="integer", required=false, description="Filter results by type.
    Type                    | Key
    ------------------------|----
    RESTAURANTS             | 1
    CAFES                   | 2
    FINE_DINING             | 3
    BAKERIES_AND_PASTRIES   | 4
    ", default="restaurant"),
     *      @Parameter("filters[htr_stars]", type="integer", required=false, description="Filter results by type.", default=""),
     *      @Parameter("filters[has_htr_stars]", type="boolean", required=false, description="Return Restaurant with HTR Stars.", default=""),
     *      @Parameter("filters[top_reviews]", type="boolean", required=false, description="Filter restaurants by top reviews in the last month.", default=""),
     *      @Parameter("filters[top_reservations]", type="boolean", required=false, description="Filter Restaurants by top confirmed and paid reservations in last month.", default=""),
     *      @Parameter("filters[is_reservable_online]", type="boolean", required=false, description="Filter Restaurants by if they are available  to be reserved online or not.", default=""),
     *      @Parameter("filters[is_trendy]", type="boolean", required=false, description="Filter Restaurants by if they are have checked by restaurant admin as trendy.", default=""),
     *      @Parameter("filters[allow_or_condition]", type="boolean", required=false, description="Filter Restaurants by Or not and.", default=""),
     * })
     */
    public function index(Requests\RestaurantRequest $request)
    {
        return $this->response->created('', $this->handleFilters());
    }

    /**
     * Create New Restaurant
     *
     * @Post("/")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("name", type="string", required=true, description="Restaurant Name.", default=""),
     *      @Parameter("slug", type="string", required=true, description="URL friendly version of the restaurant title", default=""),
     *      @Parameter("address", type="string", required=true, description="Restaurant address.", default=""),
     *      @Parameter("city_id", type="integer", required=true, description="Id of city where restaurant located.", default=""),
     *      @Parameter("latitude", type="float", required=true, description="The latitude of restaurant.", default=""),
     *      @Parameter("longitude", type="float", required=true, description="The longitude of restaurant.", default=""),
     *      @Parameter("phone", type="integer", required=true, description="Restaurant Phone.", default=""),
     *      @Parameter("email", type="string", required=true, description="Restaurant Email.", default=""),
     *      @Parameter("description", type="string", required=true, description="Restaurant Description.", default=""),
     *      @Parameter("dress_code", type="integer", required=true, description="Restaurant Dress code.", default=""),
     *      @Parameter("facebook", type="string", required=true, description="Restaurant Facebook page.", default=""),
     *      @Parameter("twitter", type="string", required=true, description="Restaurant Twitter account.", default=""),
     *      @Parameter("instagram", type="string", required=true, description="Restaurant page in instagaram.", default=""),
     *      @Parameter("snap_chat", type="string", required=true, description="Restaurant page in snap chat.", default=""),
     *      @Parameter("logo", type="string", required=true, description="Logo image of restaurant in base64 format.", default=""),
     *      @Parameter("featured_image", type="string", required=true, description="Featured image in base 64 format.", default=""),
     *      @Parameter("owner_id", type="string", required=true, description="The id for the restaurant owner.", default=""),
     *      @Parameter("price_from", type="float", required=true, description="The start value of price range for restaurant.", default=""),
     *      @Parameter("price_to", type="float", required=true, description="The end value of price range of restaurant.", default=""),
     *      @Parameter("restaurant_managers[]", type="array", required=true, description="An array of users ids that should be the managers of restaurants.", default=""),
     *      @Parameter("htr_stars", type="integer", required=true, description="the value of star that should the restaurant takes.", default=""),
     *      @Parameter("in_out_door", type="integer", required=true, description="The Restaurant type in door or outdoor or both.
    Type                    | Key
    ------------------------|----
    In Door                 | 1
    Out Door                | 2
    Both                    | 3
    ", default=""),
     *      @Parameter("type", type="integer", required=true, description="Every created element must has a type of four types
    Type                    | Key
    ------------------------|----
    RESTAURANTS             | 1
    CAFES                   | 2
    FINE_DINING             | 3
    BAKERIES_AND_PASTRIES   | 4
    ", default=""),
     *      @Parameter("categories[]", type="array", required=true, description="An array of categories that restaurant belongs to.", default=""),
     *      @Parameter("facilities[]", type="array", required=true, description="An array  of facilities the restaurant has.", default=""),
     * })
     */
    public function store(Requests\RestaurantRequest $request)
    {

        //Get Inputs
        $inputs = $request->only(Requests\RestaurantRequest::getFields());

        $inputs = Requests\RestaurantRequest::removeParent($inputs);

        \DB::beginTransaction();

        if (is_null($inputs['allow_job_vacancies'])) {
            $inputs['allow_job_vacancies'] = 0;
        }

        if (is_null($inputs['reservable_online'])) {
            $inputs['reservable_online'] = 0;
        }

        if (is_null($inputs['is_trendy'])) {
            $inputs['is_trendy'] = 0;
        }

        if (!$inputs['owner_id']) {
            $inputs['owner_id'] = NULL;
        }

        //Create Restaurant
        $restaurant = Restaurant::create($inputs);

        $restaurant->save();

        //Categories
        $restaurant->categories()->attach($inputs['categories']);

        //Facilities`
        $restaurant->facilities()->attach($inputs['facilities']);
        //Store Images
        $this->RestaurantStoreImages($request, $restaurant);

        \DB::commit();

        $restaurant = Restaurant::with('logo', 'featured')->find($restaurant->id);

        return $this->response->created('', $restaurant);
    }

    /**
     * View Restaurant information and details
     *
     * @Get("/{restaurant_slug}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="string", required=true, description="Restaurant Slug. the friendly url."),
     *      @Parameter("with_opening_days", type="boolean", required=true, description="When this flag is enabled Result will contains opening days."),
     *      @Parameter("with_reviews_count", type="boolean", required=true, description="When this flag is enabled Result will contains count of reviews."),
     *      @Parameter("with_branch_count", type="boolean", required=true, description="When this flag is enabled Result will contains count of branches."),
     * })
     */
    public function show($slug, Requests\RestaurantRequest $request)
    {
        $restaurant = Restaurant::with(['owner' => function ($q) {
            $q->select('id', 'name', 'username');
        }, 'logo', 'featured', 'categories', 'managers', 'city', 'facilities'])->whereSlug($slug)->firstOrFail();

        $openingDaysFlag = $request->input('with_opening_days', false);
        $reviewsCountFlag = $request->input('with_reviews_count', false);
        $branchCountFlag = $request->input('with_branch_count', false);

        $restaurant->reviews_count = $this->restaurantsItemsCount($reviewsCountFlag, $slug, 'rates');
        $restaurant->branches_count = $this->restaurantsItemsCount($branchCountFlag, $slug, 'branch');

        $restaurant->opening_days = $this->getOpeningDays($openingDaysFlag, $restaurant);
        $restaurant->suggested = $this->getSuggestedRestaurant($openingDaysFlag, $restaurant);

        return $this->response->created('', $restaurant);

    }

    /**
     * Update the specified Restaurant
     *
     * @PUT("/slug")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("name", type="string", required=false, description="Restaurant Name.", default=""),
     *      @Parameter("slug", type="string", required=false, description="URL friendly version of the restaurant title", default=""),
     *      @Parameter("address", type="string", required=false, description="Restaurant address.", default=""),
     *      @Parameter("city_id", type="integer", required=true, description="Id of city where restaurant located.", default=""),
     *      @Parameter("latitude", type="float", required=false, description="The latitude of restaurant.", default=""),
     *      @Parameter("longitude", type="float", required=false, description="The longitude of restaurant.", default=""),
     *      @Parameter("phone", type="integer", required=false, description="Restaurant Phone.", default=""),
     *      @Parameter("email", type="string", required=false, description="Restaurant Email.", default=""),
     *      @Parameter("description", type="string", required=false, description="Restaurant Description.", default=""),
     *      @Parameter("dress_code", type="integer", required=false, description="Restaurant Dress code.", default=""),
     *      @Parameter("facebook", type="string", required=false, description="Restaurant Facebook page.", default=""),
     *      @Parameter("twitter", type="string", required=false, description="Restaurant Twitter account.", default=""),
     *      @Parameter("instagram", type="string", required=false, description="Restaurant page in instagaram.", default=""),
     *      @Parameter("snap_chat", type="string", required=true, description="Restaurant page in snap chat.", default=""),
     *      @Parameter("logo", type="string", required=false, description="Logo image of restaurant in base64 format.", default=""),
     *      @Parameter("featured_image", type="string", required=false, description="Featured image in base 64 format.", default=""),
     *      @Parameter("owner_id", type="string", required=false, description="The id for the restaurant owner.", default=""),
     *      @Parameter("price_from", type="float", required=false, description="The start value of price range for restaurant.", default=""),
     *      @Parameter("price_to", type="float", required=false, description="The end value of price range of restaurant.", default=""),
     *      @Parameter("restaurant_managers[]", type="array", required=false, description="An array of users ids that should be the managers of restaurants.", default=""),
     *      @Parameter("htr_stars", type="integer", required=false, description="the value of star that should the restaurant takes.", default=""),
     *      @Parameter("in_out_door", type="integer", required=true, description="The Restaurant type in door or outdoor or both.
    Type                    | Key
    ------------------------|----
    In Door                 | 1
    Out Door                | 2
    Both                    | 3
    ", default=""),
     *      @Parameter("type", type="integer", required=true, description="Every created element must has a type of four types
    Type                    | Key
    ------------------------|----
    RESTAURANTS             | 1
    CAFES                   | 2
    FINE_DINING             | 3
    BAKERIES_AND_PASTRIES   | 4
    ", default=""),     *      @Parameter("categories[]", type="array", required=false, description="An array of categories that restaurant belongs to.", default=""),
     *      @Parameter("facilities[]", type="array", required=true, description="An array  of facilities the restaurant has.", default=""),
     * })
     */
    public function update(Requests\RestaurantRequest $request, $slug)
    {
        $restaurant = Restaurant::with(['logo', 'featured'])->whereSlug($slug)->firstOrFail();
        //Get Inputs
        $inputs = $request->only(Requests\RestaurantRequest::getFields());

        //Translation Fields
        $inputs = Requests\RestaurantRequest::removeParent($inputs);

        if (is_null($inputs['allow_job_vacancies'])) {
            $inputs['allow_job_vacancies'] = 0;
        }

        if (is_null($inputs['reservable_online'])) {
            $inputs['reservable_online'] = 0;
        }

        if (is_null($inputs['is_trendy'])) {
            $inputs['is_trendy'] = 0;
        }

        \DB::beginTransaction();

        $inputsToSave = array_filter($inputs);

        $inputsToSave['amount'] = (int)$request->get('amount', 0);

        if (User::getCurrentUser()->hasRole(Role::SUPER_ADMIN)) {
            $inputsToSave['owner_id'] = isset($inputsToSave['owner_id']) ? $inputsToSave['owner_id'] : NULL;
            $inputsToSave['allow_job_vacancies'] = (int)$request->get('allow_job_vacancies', 0);
            $inputsToSave['reservable_online'] = (int)$request->get('reservable_online', 0);
            $inputsToSave['is_trendy'] = (int)$request->get('is_trendy', 0);
            $inputsToSave['htr_stars'] = (int)$request->get('htr_stars', 0);
        }

        $inputsToSave['email'] = isset($inputsToSave['email']) ? $inputsToSave['email'] : NULL;

        //Update Restaurant
        $restaurant->fill($inputsToSave)->save();

        if (!empty($inputs['categories'])) {
            //Categories
            $restaurant->categories()->sync($inputs['categories']);
        }

        if (!empty($inputs['facilities'])) {
            //Facilities
            $restaurant->facilities()->sync($inputs['facilities']);
        }

        if (in_array(-1, $inputs['managers'])) {

            $restaurant->managers()->sync([]);

        } elseif (is_array($inputs['managers']) && !empty(array_filter($inputs['managers']))) {

            //Managers
            $restaurant->managers()->sync(array_filter($inputs['managers']));
        }

        //Store Images
        $this->RestaurantStoreImages($request, $restaurant);

        \DB::commit();

        //Get restaurant
        $restaurant = Restaurant::with(['logo', 'featured', 'owner' => function ($q) {
            $q->select('id', 'name', 'username');
        }])->find($restaurant->id);

        return $this->response->created('', $restaurant);
    }

    /**
     * Delete specified restaurant
     *
     * @Delete("/{restaurant_slug}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="string", required=true, description="Restaurant Slug. the friendly url."),
     * })
     */
    public function destroy($slug, Requests\RestaurantRequest $request)
    {
        $restaurant = Restaurant::whereSlug($slug)->delete();

        if (!$restaurant) {
            return $this->response->errorNotFound(trans('Restaurant not found!'));
        }

        return $this->response->created('', $restaurant);
    }

    /**
     * Get Near By Restaurant
     *
     * @Get("/nearby/{longitude}/{latitude}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("longitude", type="float", required=true, description="longitude.", default=10),
     *      @Parameter("latitude", type="float", required=true, description="Latitude.", default=""),
     * })
     */
    public function nearByRestaurant($lat, $long)
    {
        $validator = Validator::make([
            'lat' => $lat,
            'long' => $long,
        ], [
            'lat' => 'latitude',
            'long' => 'longitude'
        ]);

        if ($validator->fails()) {
            return $this->response->errorBadRequest(trans('Latitude or Longditude value is Ivalid.'));
        }

        $with = ['owner' => function ($q) {
            $q->select('id', 'name', 'username');
        }, 'logo', 'featured'];

        $restaurants = Restaurant::with($with)->nearTo($lat, $long)->get();

        return $this->response->created('', $restaurants);
    }

    /**
     * Handle filters
     *
     * @return mixed
     */
    protected function handleFilters()
    {
        $filters = Request::get('filters', []);

        $perPage = Request::get('per_page', 10);

        $orderBy = Request::get('order', 'id') ? Request::get('order', 'id') : 'id';

        $orderDir = Request::get('order_type', 'desc') ? Request::get('order_type', 'desc') : 'desc';

        if ($perPage == -1) {
            $perPage = Restaurant::all()->count();
        }

        $searchParams = [
            Request::get('search', false) ? Request::get('search', false) : false,
        ];

        if (array_filter($searchParams)) {
            $searchQuery = implode(' ', $searchParams);
        } else {
            $searchQuery = false;
        }

        $with = ['owner' => function ($q) {
            $q->select('id', 'name', 'username', 'rate_score');
        }, 'logo', 'featured', 'city', 'facilities', 'categories', 'openingDays'];

        if ($searchQuery) {
            $restaurants = Restaurant::with($with)->search($searchQuery, 1)->filters($filters)->orderBy($orderBy, $orderDir)->paginate($perPage);
        } else {
            $restaurants = Restaurant::with($with)->filters($filters)->orderBy($orderBy, $orderDir)->paginate($perPage);
        }

        return $restaurants;
    }

    /**
     * Request a Review
     *
     * @Get("{restaurant_id}/request-review/{user_id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_id", type="integer", required=true, description="The restaurant id."),
     *      @Parameter("user_id", type="integer", required=true, description="The user id."),
     * })
     */
    public function requestReview(Restaurant $restaurant, User $user)
    {
        $currentUser = User::getCurrentUser();

        $this->authorizeForUser($currentUser, 'requestReview', $restaurant);

        try {
            Event::fire(new RequestReview($user, $restaurant));
        } catch (PushNotificationException $e) {
            return $this->response->created('', [trans('Email has been sent the customer')]);
        }

        return $this->response->created('', [trans('Email has been sent to the user')]);
    }

    public function removeQuotes()
    {
        $restaurants = Restaurant::all();
        foreach ($restaurants as $restaurant) {
            $restInput['name'] = str_replace('"', '', $restaurant->name);
            $restaurant->fill($restInput)->save();
        }
        var_dump("Updated");
    }
}
