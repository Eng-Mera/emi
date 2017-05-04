@extends('emails.layouts.main')

@section('contentheader_title')
    {{ trans('Export Sheet') }}
@stop

@section('main-content')
    <div class="box">
        <div class="box-body">
            <p>
                {{ trans('Hello') }}
            </p>

            <p>
                {{ trans('Kindly find attached walkin file') }}
            </p>

            <p>
                {{ trans('With best regards,') }}
            </p>
        </div>
    </div>
@stop