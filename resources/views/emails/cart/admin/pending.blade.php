@extends('emails.layouts.main')

@section('contentheader_title')
    {{ trans('Order Pending Confirmation') }}
@stop

@section('main-content')
    <div class="box">
        <div class="box-body">
            <p>
                {{ trans('Dear') }} <?= $user->name ?>,
            </p>

            <p>
                {{ trans('A new reservation') }} #{{$reservation['id']}} {{ trans('is pending approval.') }}
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

            <p>
                {{ trans('With best regards,') }}
            </p>

            <p>
                {{ trans('Sales Team') }}
            </p>

        </div>
    </div>
@stop