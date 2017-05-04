@extends('emails.layouts.main')

@section('contentheader_title')
    {{ trans('Order Rejected') }}
@stop

@section('main-content')
    <div class="box">
        <div class="box-body">
            <p>
                {{ trans('Dear') }} <?= $user->name ?>,
            </p>

            <p>
                {{ trans('Unfortunately, your reservation ') }} #{{$reservation['id']}} {{ trans(' couldn\'t be processed because we\'re fully booked at the moment.') }}
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