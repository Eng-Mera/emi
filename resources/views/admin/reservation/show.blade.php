@extends('layouts.backend')

@section('htmlheader_title')
    Reservation
@endsection

@section('contentheader_title')
    Reservation
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/reservation') }}"><i class="fa fa-dashboard"></i>Reservation</a></li>
@endsection

@section('breadcrumb_current')
    Show Reservation
@endsection

@section('main-content')
    <h1>{{ $reservation->id }} <small></small></h1>

    <pre>{{ json_encode($reservation, JSON_PRETTY_PRINT) }};</pre>

@stop