<?php

namespace App\Http\Controllers\APIs;

use App\PaymentMethod;
use App\Reservation;
use Illuminate\Http\Request;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Helpers\CartTrait;
use App\Http\Controllers\Controller;

use App\Http\Requests;

class ApiPaymentController extends Controller
{
    use CartTrait;

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
    public function store(StorePaymentRequest $request)
    {
        $fields = $request->only([
            'payment_method_id',
            'reservation_id'
        ]);

        /**
         * @todo check first if key-value is reservation_id or subscription_id or another entity
         */

        $payment_method_id = isset($request['payment_method_id']) ? $request['payment_method_id'] : PaymentMethod::DEFAULT_PAYMENT_METHOD_ID;
        // call trait to do the heavy weight lifting job
        $this->doPay($request['reservation_id'], Reservation::class, $payment_method_id);

        // return url if 3D payment

        // return response if 2D payment
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

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
}
