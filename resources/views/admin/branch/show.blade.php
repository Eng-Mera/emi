@extends('layouts.backend')

@section('htmlheader_title')
    Branch - {{ $branch->slug }}
@endsection

@section('contentheader_title')
    {{ $branch->slug }}
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/restaurant') }}"><i class="fa fa-dashboard"></i>Restaurant</a></li>
@endsection

@section('breadcrumb_current')
    Show Branch
@endsection

@section('main-content')
    <h1>{{ $branch->slug }} <small></small></h1>

    <pre>{{ json_encode($branch, JSON_PRETTY_PRINT) }};</pre>
@stop