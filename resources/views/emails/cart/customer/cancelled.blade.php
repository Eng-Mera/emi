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
                {{ trans('Your reservation') }} #{{$reservation['id']}} {{ trans('was cancelled.') }}
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