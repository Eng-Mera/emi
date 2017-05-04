@extends('emails.layouts.main')

@section('contentheader_title')
    {{ trans('Advertisement Request') }}
@stop

@section('main-content')
    <div class="box">
        <div class="box-body">
            {{ $mail }}
        </div>
    </div>
@stop

