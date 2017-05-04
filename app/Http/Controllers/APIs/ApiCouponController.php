<?php

namespace App\Http\Controllers\APIs;

use App\Coupon;
use App\Events\UserLevelWasPromoted;
use App\Http\Helpers\PromotableTrait;
use App\Reservation;
use App\User;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;

class ApiCouponController extends Controller
{
    /**
     * Promotable
     */
    use PromotableTrait;

    /**
     * Dingo
     */
    use Helpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function assign(\Illuminate\Http\Request $request)
    {
        $fields = $request->only([
            'value',
            'user_id',
            'type',
            'expired_at',
            'reusable',
            'reservation_id'
        ]);

        $rules = [
            'value' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
            'type' => 'sometimes|in:' . Coupon::COUPON_TYPE_FIXED . ',' . Coupon::COUPON_TYPE_PERCENTAGE,
            'expired_at' => 'sometimes|date|after:now',
            'reusable' => 'boolean',
            'reservation_id' => 'required|exists:reservations,id'
        ];

        $validator = \Validator::make($fields, $rules);

        $restaurant = User::getManagersRestaurant();

        if (!$restaurant) {
            return $this->response->errorBadRequest(trans('This manager has not been assigned to any restaurant yet'));
        }

        if ($validator->fails()) {
            return $this->response->errorBadRequest($validator->errors());
        }

        $reservation = Reservation::find($fields['reservation_id']);

        if ($reservation->received_a_coupon == 1) {
            return $this->response->errorBadRequest('This user already received a coupon');
        }

        $fields['restaurant_id'] = $restaurant->id;

        $data = $this->setCouponDefaults($fields);

        $result = Coupon::create($data);

        if ($result) {

            $reservation->received_a_coupon = 1;
            $reservation->save();

            \Event::fire(new UserLevelWasPromoted($result->user, $result));
        }

        return $this->response->created($request->getUri(), $result);
    }


}
