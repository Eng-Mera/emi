@extends('layouts.backend')

@section('htmlheader_title')
    User - {{ $facility->name }}
@endsection

@section('contentheader_title')
    {{ $facility->name }}
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/facility') }}"><i class="fa fa-dashboard"></i>Facilities</a></li>
@endsection

@section('breadcrumb_current')
    Show Facility
@endsection

@section('main-content')
    <h1>{{ $facility->name }} <small></small></h1>

    <pre>{{ json_encode($facility, JSON_PRETTY_PRINT) }};</pre>

@stop