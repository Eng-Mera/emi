@extends('emails.layouts.main')

@section('contentheader_title')
    {{ trans('Apply Promo Code & Enjoy Discounts') }}
@stop

@section('main-content')
    <div class="box">
        <div class="box-body">
            <p>
                {{ trans('Dear') }} <?= $customer->name ?>,
            </p>

            <p>
                {{ trans('Apply this promo code') }} <b>{{$promo->code}}</b> {{ trans('to enjoy') }}
                {{$promo->value}}
                @if($promo->type == \App\Coupon::COUPON_TYPE_PERCENTAGE)
                    %
                @else
                    EGP
                @endif
                {{trans('discount')}}
            </p>

            <p>

            @if($promo->reusable)
                <li>{{trans('The coupon is reusable and you can use it with multiple reservations')}}.</li>
            @else
                <li>{{trans('The coupon can be used for one reservation')}}.</li>
            @endif
            @if($promo->expired_at)
                <li>{{ trans('The coupon expires at ') . $promo->expired_at }} .</li>
                @endif

                </p>


                <p>
                    {{ trans('With best regards,') }}
                </p>

                <p>
                    {{ \Config::get('nilecode.emails.sales.name')}}
                </p>

        </div>
    </div>
@stop

