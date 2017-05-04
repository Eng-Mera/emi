@extends('layouts.backend')

@section('htmlheader_title')
    User - {{ $restaurant->name }}
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
        <h1>{{ $category->category_name }} <small></small></h1>

        <pre>{{ json_encode($category, JSON_PRETTY_PRINT) }};</pre>

@stop