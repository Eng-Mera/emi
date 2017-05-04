@extends('layouts.backend')

@section('htmlheader_title')
    User - {{ $jobtitle->name }}
@endsection

@section('contentheader_title')
    {{ $jobtitle->name }}
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/job-title') }}"><i class="fa fa-dashboard"></i>Job Title</a></li>
@endsection

@section('breadcrumb_current')
    Show Job Title
@endsection

@section('main-content')
    <h1>{{ $jobtitle->job_title }}
        <small></small>
    </h1>

    <pre>{{ json_encode($jobtitle, JSON_PRETTY_PRINT) }};</pre>

@stop