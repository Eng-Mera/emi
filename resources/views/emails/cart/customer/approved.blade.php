@extends('emails.layouts.main')

@section('contentheader_title')
    {{ trans('Order Approval') }}
@stop

@section('main-content')
    <div class="box">
        <div class="box-body">
            <p>
                {{ trans('Dear') }} <?= $user->name ?>,
            </p>

            <p>
                {{ trans('Your reservation') }} #{{$reservation['id']}} {{ trans('was approved.') }}
            </p>

            <p>
                {{ trans('With best regards,') }}
            </p>

            <p>
                {{ trans('Sales Team') }}
            </p>

            <?php

            $salt = "nilecode";
            $secret = md5($salt . $reservation['total'] . $reservation['id']);

            ?>
            <?php if($reservation->amount): ?>
            <p>
                <a href="{{ env('FRONT_URL') }}/restaurant/checkout?amount={{$reservation['total']}}&id={{$reservation['id']}}&secret={{$secret}}"
                   class="btn btn-primary">Go to Payment</a>
            </p>
            <?php endif; ?>

            <p>
                {{ trans('Or , If you want to cancel the reservation .. click here') }}
            </p>

            <p>

                <a href="{{env('FRONT_URL')}}restaurant/cancel?id={{$reservation['id']}}" class="btn btn-danger">Cancel</a>
            </p>

        </div>
    </div>
@stop