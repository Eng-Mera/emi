<?php

/**
 * User resource representation.
 *
 * @Resource("Users", uri="/users")
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\APIs\ApiOpeningDayController;
use App\MenuItem;
use App\Http\Requests;
use App\OpeningDay;
use App\Restaurant;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class AdminOpeningDayController extends ApiOpeningDayController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Requests\OpeningDayRequest $request, $restaurantSlug)
    {
        $res = Restaurant::whereSlug($restaurantSlug)->firstOrFail();

        $this->authorize('listOpeningDay', $res);

        if (Request::ajax()) {

            $paging = $this->getDatatablePaging(['id', 'name', 'slug', 'price', 'popular_dish', 'created_at']);

            $openingday = $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->get(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/opening-days', $paging);

            return $this->datatables($openingday);
        }

        return view('admin.openingdays.index')->with(['restaurant_slug' => $restaurantSlug]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Requests\OpeningDayRequest $request, $restaurantSlug)
    {
        $openingday = new OpeningDay();

        $timestamp = strtotime('next Sunday');

        $days = OpeningDay::getDays();

        return view('admin.openingdays.create')->with(['openingDay' => $openingday, 'restaurant_slug' => $restaurantSlug, 'week_days' => $days]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Requests\RestaurantRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\OpeningDayRequest $request, $restSlug)
    {
        $inputs = $request->all();

        $openingday = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->attach($request->allFiles())
            ->post(env('API_VERSION', 'v1') . '/restaurant/' . $restSlug . '/opening-days', $inputs);

        return Redirect::route('admin.restaurant.opening-days.edit', [$restSlug, $openingday->id])->with('content-message', trans('Opening day has been created Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function show(Requests\OpeningDayRequest $request, $restaurantSlug, $slug)
    {
        $openingday = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/opening-days/' . $slug);

        return view('admin.openingdays.show')->withOpeningDay($openingday);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Requests\OpeningDayRequest $request, $restaurantSlug, $id)
    {
        $openingday = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/opening-days/' . $id);


        $days = OpeningDay::getDays();

        return view('admin.openingdays.edit')->with(['openingDay' => $openingday, 'restaurant_slug' => $restaurantSlug, 'week_days' => $days]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Requests\RestaurantRequest $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\OpeningDayRequest $request, $restaurantSlug, $id)
    {
        $inputs = $request->all();

        $openingday = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->attach($request->allFiles())
            ->post(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/opening-days/' . $id, $inputs);

        return Redirect::route('admin.restaurant.opening-days.edit', [$restaurantSlug, $openingday->id])->with('content-message', trans('Menu Item has been updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Requests\OpeningDayRequest $request, $restaurantSlug, $id)
    {
        $openingday = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->delete(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/opening-days/' . $id);

        if ($openingday) {
            $msg = 'Opening day has been deleted successfully!';
            return Redirect::route('admin.restaurant.opening-days.index', $restaurantSlug)->with('content-message', trans($msg));
        } else {
            $msg = 'Opening day has already been deleted!';
            return Redirect::route('admin.restaurant.opening-days.index', $restaurantSlug)->with('error-message', trans($msg));
        }

    }
}
