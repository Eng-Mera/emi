<?php

/**
 * User resource representation.
 *
 * @Resource("Users", uri="/users")
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\APIs\ApiMenuItemController;
use App\MenuItem;
use App\Restaurant;
use App\Role;
use App\Lang;
use App\Http\Requests;
use App\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class AdminMenuItemController extends ApiMenuItemController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Requests\MenuItemRequest $request, $restaurantSlug)
    {
        $res = Restaurant::whereSlug($restaurantSlug)->firstOrFail();

        $this->authorize('listMenuItems', $res);

        if (Request::ajax()) {

            $paging = $this->getDatatablePaging(['id', 'name', 'slug', 'price', 'popular_dish', 'created_at']);

            $menuItem = $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->get(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/menu-item', $paging);

            return $this->datatables($menuItem);

        }

        return view('admin.menuitem.index')->with(['restaurant_slug' => $restaurantSlug]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Requests\MenuItemRequest $request, $restaurantSlug)
    {
        $menuItem = new MenuItem();

        $locales = Lang::all();

        $dishCategory = ['' => 'Select Dish Category'] + $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->get(env('API_VERSION', 'v1') . '/dish-category', ['per_page' => -1])->pluck('category_name', 'id')->toArray();

        return view('admin.menuitem.create')->with(['locales' => $locales, 'menuItem' => $menuItem, 'restaurant_slug' => $restaurantSlug, 'dish_category' => $dishCategory]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Requests\RestaurantRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\MenuItemRequest $request, $restSlug)
    {
        $inputs = $request->all();

        $menuItem = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->attach($request->allFiles())
            ->post(env('API_VERSION', 'v1') . '/restaurant/' . $restSlug . '/menu-item', $inputs);

        return Redirect::route('admin.restaurant.menu-item.edit', [$restSlug, $menuItem->slug])->with('content-message', trans('Menu Item has been created Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function show(Requests\MenuItemRequest $request, $restaurantSlug, $slug)
    {

        $menuItem = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/restaurant/' . $slug);

        return view('admin.menuitem.show')->withRestaurant($menuItem);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function edit(Requests\MenuItemRequest $request, $restaurantSlug, $slug)
    {
        $locales = Lang::all();

        $menuItem = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/menu-item/' . $slug);

        $dishCategory = ['' => 'Select Dish Category'] + $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->get(env('API_VERSION', 'v1') . '/dish-category', ['per_page' => -1])->pluck('category_name', 'id')->toArray();

        return view('admin.menuitem.edit')->with(['locales' => $locales, 'menuItem' => $menuItem, 'restaurant_slug' => $restaurantSlug, 'dish_category' => $dishCategory]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Requests\RestaurantRequest $request
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\MenuItemRequest $request, $restaurantSlug, $slug)
    {

        $inputs = $request->all();

        if (!isset($inputs['popular_dish'])) {
            $inputs['popular_dish'] = 0;
        }

        $menuItem = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->attach($request->allFiles())
            ->post(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/menu-item/' . $slug, $inputs);

        return Redirect::route('admin.restaurant.menu-item.edit', [$restaurantSlug, $menuItem->slug])->with('content-message', trans('Menu Item has been updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy(Requests\MenuItemRequest $request, $restaurantSlug, $slug)
    {

        $menuItem = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->delete(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/menu-item/' . $slug);

        if ($menuItem) {
            $msg = 'Menu Item has been deleted successfully!';
            return Redirect::route('admin.restaurant.menu-item.index', $restaurantSlug)->with('content-message', trans($msg));
        } else {
            $msg = 'Menu Item has already been deleted!';
            return Redirect::route('admin.restaurant.menu-item.index', $restaurantSlug)->with('error-message', trans($msg));
        }

    }
}
