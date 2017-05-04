<?php

/**
 * User resource representation.
 *
 * @Resource("Users", uri="/users")
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\APIs\ApiReservationPolicyController;
use App\ReservationPolicy;
use App\Restaurant;
use App\Lang;
use App\Http\Requests;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class AdminReservationPolicyController extends ApiReservationPolicyController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Requests\ReservationPolicyRequest $request, $restaurantSlug)
    {
        $res = Restaurant::whereSlug($restaurantSlug)->firstOrFail();

        $this->authorize('listReservationPolicy', $res);

        if (Request::ajax()) {

            $paging = $this->getDatatablePaging(['id', 'name', 'start_date', 'end_date', 'created_at', 'updated_at']);

            $reservationPolicy = $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->get(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/reservation-policy', $paging);

            return $this->datatables($reservationPolicy);

        }

        return view('admin.reservation-policy.index')->with(['restaurant_slug' => $restaurantSlug]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Requests\ReservationPolicyRequest $request, $restaurantSlug)
    {
        $reservationPolicy = new ReservationPolicy();

        return view('admin.reservation-policy.create')->with(['reservationPolicy' => $reservationPolicy, 'restaurant_slug' => $restaurantSlug]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Requests\RestaurantRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\ReservationPolicyRequest $request, $restSlug)
    {

        $inputs = $request->all();

        $reservationPolicy = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->post(env('API_VERSION', 'v1') . '/restaurant/' . $restSlug . '/reservation-policy', $inputs);

        return Redirect::route('admin.restaurant.reservation-policy.edit', [$restSlug, $reservationPolicy->id])->with('content-message', trans('Reservation Policyhas been created Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function show(Requests\ReservationPolicyRequest $request, $restaurantSlug, $id)
    {

        $reservationPolicy = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/reservation-policy/'. $id);

        return view('admin.reservation-policy.show')->withRestaurant($reservationPolicy);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Requests\ReservationPolicyRequest $request, $restaurantSlug, $id)
    {
        $reservationPolicy = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/reservation-policy/' . $id);

        return view('admin.reservation-policy.edit')->with(['reservationPolicy' => $reservationPolicy, 'restaurant_slug' => $restaurantSlug]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Requests\RestaurantRequest $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\ReservationPolicyRequest $request, $restaurantSlug, $id)
    {
        $inputs = $request->all();

        $reservationPolicy = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->attach($request->allFiles())
            ->post(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/reservation-policy/' . $id, $inputs);

        return Redirect::route('admin.restaurant.reservation-policy.edit', [$restaurantSlug, $reservationPolicy->id])->with('content-message', trans('Reservation Policyhas been updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Requests\ReservationPolicyRequest $request, $restaurantSlug, $id)
    {

        $reservationPolicy = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->delete(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/reservation-policy/' . $id);

        if ($reservationPolicy) {
            $msg = 'Reservation Policy has been deleted successfully!';
            return Redirect::route('admin.restaurant.reservation-policy.index', $restaurantSlug)->with('content-message', trans($msg));
        } else {
            $msg = 'Reservation Policy has already been deleted!';
            return Redirect::route('admin.restaurant.reservation-policy.index', $restaurantSlug)->with('error-message', trans($msg));
        }

    }
}
