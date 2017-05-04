@extends('layouts.backend')

@section('htmlheader_title')
    City - {{ $city->city_name }}
@endsection

@section('contentheader_title')
    {{ $city->city_name }}
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/city') }}"><i class="fa fa-dashboard"></i>Cities</a></li>
@endsection

@section('breadcrumb_current')
    Show City
@endsection

@section('main-content')
    <h1>{{ $city->city_name }}
        <small></small>
    </h1>

    <pre>{{ json_encode($city, JSON_PRETTY_PRINT) }};</pre>

@stop