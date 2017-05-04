@extends('layouts.backend')

@section('htmlheader_title')
    Claim - {{ $claim->user->name }}
@endsection

@section('contentheader_title')
    {{ $claim->user->name }}
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/claim') }}"><i class="fa fa-dashboard"></i>Claims</a></li>
@endsection

@section('breadcrumb_current')
    Show Claim
@endsection

@section('main-content')
    <h1>{{ $claim->user->name }} <small></small></h1>

    <pre>{{ json_encode($claim, JSON_PRETTY_PRINT) }};</pre>
@stop