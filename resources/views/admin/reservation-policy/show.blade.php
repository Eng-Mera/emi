@extends('layouts.backend')

@section('htmlheader_title')
    Reservation Policy
@endsection

@section('contentheader_title')
    {{ $reservationPolicy->name }}
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/restaurant') }}"><i class="fa fa-dashboard"></i>Restaurant</a></li>
@endsection

@section('breadcrumb_current')
    Show
@endsection

@section('main-content')
    <h1>{{ $reservationPolicy->name }}
        <small></small>
    </h1>

    <pre>{{ json_encode($reservationPolicy, JSON_PRETTY_PRINT) }};</pre>
@stop