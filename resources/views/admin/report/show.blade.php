@extends('layouts.backend')

@section('htmlheader_title')
    Report - {{ $report->id }}
@endsection

@section('contentheader_title')
    {{ $report->id }}
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/report') }}"><i class="fa fa-dashboard"></i>Reports</a></li>
@endsection

@section('breadcrumb_current')
    Show Report
@endsection

@section('main-content')
    <h1>{{ $report->id }} <small></small></h1>

    <pre>{{ json_encode($report, JSON_PRETTY_PRINT) }};</pre>

    {{--@if (Auth::user() && Auth::user()->id == $restaurant->owner->id)--}}
        {{--<a href="{{ url('admin/user/edit', $restaurant->username) }}">{{ trans('Edit Restaurant') }}</a>--}}
    {{--@endif--}}
@stop