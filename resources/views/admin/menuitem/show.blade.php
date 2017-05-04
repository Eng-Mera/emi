@extends('layouts.backend')

@section('htmlheader_title')
    User - {{ $menuItem->name }}
@endsection

@section('contentheader_title')
    {{ $menuItem->name }}
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/restaurant') }}"><i class="fa fa-dashboard"></i>Restaurant</a></li>
@endsection

@section('breadcrumb_current')
    Show MenuItem
@endsection

@section('main-content')
        <h1>{{ $menuItem->name }} <small></small></h1>

        <pre>{{ json_encode($menuItem, JSON_PRETTY_PRINT) }};</pre>
@stop