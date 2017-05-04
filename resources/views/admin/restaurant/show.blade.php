@extends('layouts.backend')

@section('htmlheader_title')
    Restaurant - {{ $restaurant->name }}
@endsection

@section('contentheader_title')
    {{ $restaurant->name }}
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/restaurant') }}"><i class="fa fa-dashboard"></i>Restaurant</a></li>
@endsection

@section('breadcrumb_current')
    Show Restaurant
@endsection

@section('main-content')
        <h1>{{ $restaurant->name }} <small></small></h1>

        <pre>{{ json_encode($restaurant, JSON_PRETTY_PRINT) }};</pre>


@stop