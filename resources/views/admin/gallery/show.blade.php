@extends('layouts.backend')

@section('htmlheader_title')
    Gallery Item - {{ $galleryItem->name }}
@endsection

@section('contentheader_title')
    {{ $galleryItem->name }}
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/restaurant') }}"><i class="fa fa-dashboard"></i>Restaurant</a></li>
@endsection

@section('breadcrumb_current')
    Show Restaurant
@endsection

@section('main-content')
    <h1>{{ $galleryItem->name }}
        <small></small>
    </h1>

    <pre>{{ json_encode($galleryItem, JSON_PRETTY_PRINT) }};</pre>

@stop