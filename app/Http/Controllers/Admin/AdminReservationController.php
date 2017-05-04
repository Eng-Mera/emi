<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\CustomValidationException;
use App\Http\Controllers\APIs\ApiReservationController;
use App\Reservation;
//use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;


use App\Restaurant;
use App\Role;
use App\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\ReservationRequest;


class AdminReservationController extends ApiReservationController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Requests\ReservationRequest $request)
    {
        if (Request::ajax()) {

            $paging = $this->getDatatablePaging(['id','number_of_people', 'time' , 'total' , 'restaurant_id','user_id', 'created_at', 'updated_at']);

            $restaurant = User::getManagersRestaurant();
            if ($restaurant)
            {
                $reservations = $this->api->version(env('API_VERSION', 'v1'))
                    ->header('webAuthKey', Config::get('api.webAuthKey'))
                    ->get(env('API_VERSION', 'v1') . '/reservation?restaurant_id='.$restaurant->id, $paging);
            }
            else
            {
                $reservations = $this->api->version(env('API_VERSION', 'v1'))
                    ->header('webAuthKey', Config::get('api.webAuthKey'))
                    ->get(env('API_VERSION', 'v1') . '/reservation', $paging);
            }

            return $this->datatables($reservations);

        }

        return view('admin.reservation.index');
    }


    public function accept(\Illuminate\Http\Request $request, Reservation $reservation)
    {
        if ($reservation->status == 'PENDING') {
            $reservation = $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->patch(env('API_VERSION', 'v1') . '/reservation/' . $reservation->id . '/accept');
        } else
        {
            $msg = 'Reservation should be in Pending status!';
            return Redirect::route('admin.reservation.index')->with('content-message', trans($msg));
        }

        if ($reservation) {
            $msg = 'Reservation has been Accepted successfully!';
            return Redirect::route('admin.reservation.index')->with('content-message', trans($msg));
        } else {
            $msg = 'Reservation has already been Accepted!';
            return Redirect::route('admin.reservation.index')->with('error-message', trans($msg));
        }
        return view('admin.reservation.index');
    }

    public function arrived(\Illuminate\Http\Request $request, Reservation $reservation)
    {

        $reservation = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->patch(env('API_VERSION', 'v1') . '/reservation/' . $reservation->id . '/arrived');

        if ($reservation) {
            $msg = 'Client has been Arrived successfully!';
            return Redirect::route('admin.reservation.index')->with('content-message', trans($msg));
        } else {
            $msg = 'Client has already been Arrived before!';
            return Redirect::route('admin.reservation.index')->with('error-message', trans($msg));
        }
        return view('admin.reservation.index');
    }
    
    
    public function reject(\Illuminate\Http\Request $request, Reservation $reservation)
    {

        $reservation = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->patch(env('API_VERSION', 'v1') . '/reservation/' . $reservation->id . '/reject');

        if ($reservation) {
            $msg = 'Reservation has been Rejected successfully!';
            return Redirect::route('admin.reservation.index')->with('content-message', trans($msg));
        } else {
            $msg = 'Reservation has already been Rejected!';
            return Redirect::route('admin.reservation.index')->with('error-message', trans($msg));
        }
        return view('admin.reservation.index');
    }
    
    public function edit(Reservation $reservation)
    {
        return view('admin.reservation.edit', [
            'reservation' => $reservation
        ]);
    }

    public function change(\Illuminate\Http\Request $request, Reservation $reservation)
    {
        $inputs = $request->only([
            'date',
            'time',
            'number_of_people',
            'advance_payment',
            'coupon_code',
            'option'
        ]);
        $inputs['time'] = date('H:i',strtotime($inputs['time']));
        try{
            $reservation = $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->patch(env('API_VERSION', 'v1') . '/reservation/' . $reservation->id . '/change',$inputs);

        }
        catch (\Exception $e)
        {
            $errorMsgs = '';
            $messages = json_decode($e->getMessage());
            foreach ($messages as $errorMsg)
            {
                $errorMsgs .= $errorMsg[0] . ' . ';
            }

            return Redirect::route('admin.reservation.edit',['reservation' => $reservation])->with('error-message', trans($errorMsgs));
        }
        if ($reservation) {
            $msg = 'Reservation has been Accepted successfully!';
            return Redirect::route('admin.reservation.index')->with('content-message', trans($msg));
        } else {
            $msg = 'Reservation has already been Accepted!';
            return Redirect::route('admin.reservation.index')->with('error-message', trans($msg));
        }

        return view('admin.reservation.index');
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(\Illuminate\Http\Request $request, Reservation $reservation)
    {

        $reservation = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/reservation/' . $reservation->id);

        return view('admin.reservation.show')->withReservation($reservation);
    }
    
    public function requestReview(Restaurant $restaurant , User $user)
    {
        if ( User::getCurrentUser()->hasRole(Role::SUPER_ADMIN) or User::getManagersRestaurant()->id == $restaurant->id)
        {
            $reservation = $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->get(env('API_VERSION', 'v1') . '/restaurant/' . $restaurant->id . '/request-review/' . $user->id);
            if ($reservation)
            {
                $msg = 'Review has been Requested successfully!';
                return Redirect::route('admin.reservation.index')->with('content-message', trans($msg));
            }
        }
        $msg = 'Review has not been Requested !';
        return Redirect::route('admin.reservation.index')->with('error-message', trans($msg));
    }
    

}
