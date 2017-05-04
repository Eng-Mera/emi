<?php

/**
 * User resource representation.
 *
 * @Resource("Users", uri="/users")
 */

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Facility;
use App\Http\Controllers\APIs\ApiRestaurantController;
use App\Http\Controllers\APIs\ApiUsersController;
use App\Restaurant;
use App\Role;
use App\Lang;
use App\User;
use App\Http\Requests;
use Dingo\Api\Http\Middleware\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use RelationalExample\Model\Users;

class AdminRestaurantController extends ApiRestaurantController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Requests\RestaurantRequest $request)
    {
        if (Request::ajax()) {

            $paging = $this->getDatatablePaging(['id', 'name', 'slug', 'email', 'owner', 'created_at', 'updated_at']);

            $restaurant = $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->get(env('API_VERSION', 'v1') . '/restaurant', $paging);

            return $this->datatables($restaurant);

        }

        return view('admin.restaurant.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Requests\RestaurantRequest $request)
    {
        $restaurantManagers = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/user/users-by-role/' . Role::RESTAURANT_MANAGER);

        $restaurantAdmins = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/user/users-by-role/' . Role::RESTAURANT_ADMIN);

        $reservationManagers = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/user/users-by-role/' . Role::RESERVATION_MANAGER);

        $cities = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/city?per_page=-1')->pluck('city_name', 'id')->toArray();

        $restaurant = new Restaurant();

        $restaurantManagers = ['' => 'Select Restaurant Owner'] + array_pluck($restaurantManagers->toArray(), ['name'], 'id');
        $restaurantAdmins = array_pluck($restaurantAdmins->toArray(), ['name'], 'id');

        $categories = Category::all()->pluck('category_name', 'id');
        $facilities = Facility::all()->pluck('name', 'id');

        $locales = Lang::all();

        return view('admin.restaurant.create')->with([
            'restaurant' => $restaurant,
            'restaurant_manager' => $restaurantManagers,
            'restaurant_categories' => [],
            'categories' => ['' => 'Choose Category'] + $categories->toArray(),
            'restaurant_facilities' => [],
            'restaurant_categories' => [],
            'facilities' => ['' => 'Choose Facilities'] + $facilities->toArray(),
            'types' => ['' => 'Select Type ..'] + Restaurant::getTypes(),
            'htrStars' => ['' => 'Select HTR Stars ..'] + Restaurant::getHtrStars(),
            'restaurant_admins' => $restaurantAdmins,
            'cities' => $cities,
            'in_out_door' => ['' => 'Select ...'] + Restaurant::getInOuTDoor(),
            'locales' => $locales,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Requests\RestaurantRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\RestaurantRequest $request)
    {
        $inputs = $request->only(Requests\RestaurantRequest::getFields());

        $restaurant = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->attach($request->allFiles())
            ->post(env('API_VERSION', 'v1') . '/restaurant', $inputs);

        return Redirect::route('admin.restaurant.edit', $restaurant->slug)->with('content-message', trans('Restaurant has been created Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug, Requests\RestaurantRequest $request)
    {
        $restaurant = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/restaurant/' . $slug);

        return view('admin.restaurant.show')->withRestaurant($restaurant);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug, Requests\RestaurantRequest $request)
    {

        $restaurant = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/restaurant/' . $slug);

        $restaurantCategories = $restaurant->categories->pluck('id')->toArray();
        $categories = Category::all()->pluck('category_name', 'id');

        $restaurantFacilities = $restaurant->facilities->pluck('id')->toArray();
        $facilities = Facility::all()->pluck('name', 'id');

        $cities = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/city?per_page=-1')->pluck('city_name', 'id')->toArray();

        $restaurantManagers = [];
        $restaurantAdmins = [];

        $restaurantAdmins = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/user/users-by-role/' . Role::RESTAURANT_ADMIN);

        $restaurantManagers = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/user/users-by-role/' . Role::RESTAURANT_MANAGER);

        $reservationManager = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/user/users-by-role/' . Role::RESERVATION_MANAGER);

        $restaurantManagers = ['' => 'Select Restaurant Owner'] + array_pluck($restaurantManagers->toArray(), ['name'], 'id');
        $restaurantAdmins = array_pluck($restaurantAdmins->toArray(), ['name'], 'id');

        $reservationManagers = array_pluck($reservationManager->toArray(), ['name'], 'id');

        if(User::getCurrentUser()->hasRole([Role::RESTAURANT_MANAGER, Role::SUPER_ADMIN])){
            $restaurantAdmins = $restaurantAdmins + $reservationManagers;
        }

        $locales = Lang::all();

        return view('admin.restaurant.edit')->with([
            'restaurant' => $restaurant,
            'restaurant_categories' => $restaurantCategories,
            'categories' => ['' => 'Choose Categories ..'] + $categories->toArray(),
            'restaurant_facilities' => $restaurantFacilities,
            'facilities' => ['' => 'Choose Facilities ..'] + $facilities->toArray(),
            'types' => ['' => 'Select Type ..'] + Restaurant::getTypes(),
            'htrStars' => ['' => 'Select HTR Stars ..'] + Restaurant::getHtrStars(),
            'restaurant_admins' => $restaurantAdmins,
            'restaurant_manager' => $restaurantManagers,
            'cities' => $cities,
            'in_out_door' => ['' => 'Select ...'] + Restaurant::getInOuTDoor(),
            'locales' => $locales,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Requests\RestaurantRequest $request
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\RestaurantRequest $request, $slug)
    {
        $data = $request->all();

        if(empty($data['managers'])){
            $data['managers'] =  [-1];
        }

        $restaurant = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->attach($request->allFiles())
            ->post(env('API_VERSION', 'v1') . '/restaurant/' . $slug, $data);

        return Redirect::route('admin.restaurant.edit', $restaurant->slug)->with('content-message', trans('Restaurant has been updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug, Requests\RestaurantRequest $request)
    {
        $restaurant = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->delete(env('API_VERSION', 'v1') . '/restaurant/' . $slug);

        if ($restaurant) {
            $msg = 'Restaurant has been deleted successfully!';
            return Redirect::route('admin.restaurant.index')->with('content-message', trans($msg));
        } else {
            $msg = 'Restaurant has already been deleted!';
            return Redirect::route('admin.restaurant.index')->with('error-message', trans($msg));
        }

    }
}