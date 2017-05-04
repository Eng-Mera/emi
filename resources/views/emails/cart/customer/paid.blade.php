@extends('emails.layouts.main')

@section('contentheader_title')
    {{ trans('Order Paid') }}
@stop

@section('main-content')
    <div class="box">
        <div class="box-body">
            <p>
                {{ trans('Dear') }} <?= $user->name ?>,
            </p>

            <p>
                {{ trans('Your payment for reservation') }} #{{$reservation['id']}} {{ trans('was received successfully.') }}
            </p>

            <p>
                {{ trans('With best regards,') }}
            </p>

            <p>
                {{ trans('Sales Team') }}
            </p>

        </div>
    </div>
@stop