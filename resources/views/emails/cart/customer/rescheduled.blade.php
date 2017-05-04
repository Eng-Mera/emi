@extends('emails.layouts.main')

@section('contentheader_title')
    {{ trans('Order Rescheduled') }}
@stop

@section('main-content')
    <div class="box">
        <div class="box-body">
            <p>
                {{ trans('Dear') }} <?= $user->name ?>,
            </p>

            <p>
                {{ trans('Your reservation') }} #{{$reservation['id']}} {{ trans('was rescheduled.') }}
            </p>

            <?php

            $salt = "nilecode";
            $secret = md5($salt.$reservation['total'].$reservation['id']);

            ?>

            <p>
                <a href="{{ env('FRONT_URL') }}/restaurant/checkout?amount={{$reservation['total']}}&id={{$reservation['id']}}&secret={{$secret}}" class="btn btn-primary">Go to Payment</a>
            </p>

            <p>
                {{ trans('Or , If you want to cancel the reservation .. click here') }}
            </p>

            <p>

                <a href="{{env('FRONT_URL')}}restaurant/cancel?id={{$reservation['id']}}" class="btn btn-danger">Cancel</a>
            </p>

            @include('emails.cart.partials.reservation')

            <p>
                {{ trans('With best regards,') }}
            </p>

            <p>
                {{ trans('Sales Team') }}
            </p>

        </div>
    </div>
@stop