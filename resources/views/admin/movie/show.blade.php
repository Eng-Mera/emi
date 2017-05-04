@extends('layouts.backend')

@section('htmlheader_title')
    User - {{ $movie->name }}
@endsection

@section('contentheader_title')
    {{ $movie->name }}
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/movie') }}"><i class="fa fa-dashboard"></i>Movie</a></li>
@endsection

@section('breadcrumb_current')
    Show Movie
@endsection

@section('main-content')
        <h1>{{ $movie->name }} <small></small></h1>

        <pre>{{ json_encode($movie, JSON_PRETTY_PRINT) }};</pre>

@stop