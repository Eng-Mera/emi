@extends('emails.layouts.main')

@section('contentheader_title')
    {{ trans('Reservation Cancelled') }}
@stop

@section('main-content')
    <div class="box">
        <div class="box-body">
            <p>
                {{ trans('Dear') }} <?= $user->name ?>,
            </p>

            <p>
                {{ trans('A reservation') }} #{{$reservation['id']}} {{ trans('was cancelled.') }}
            </p>

            <p>
                {{trans('Customer Details:')}}
            </p>

            <p>
                <span>{{trans('Name')}}</span> {{$customer['name']}}
            </p>

            <p>
                <span>{{trans('E-mail')}}</span> {{$customer['email']}}
            </p>

            <p>
                <span>{{trans('Mobile')}}</span> {{$customer['phone']}}
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