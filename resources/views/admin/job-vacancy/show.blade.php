@extends('layouts.backend')

@section('htmlheader_title')
    User - {{ $job_vacancy->id }}
@endsection

@section('contentheader_title')
    {{ $job_vacancy->id }}
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/restaurant/'. $slug .'/job-vacancy') }}"><i class="fa fa-dashboard"></i>Job Vacancy</a></li>
@endsection

@section('breadcrumb_current')
    Show Job Vacancy
@endsection

@section('main-content')
    <h1>{{ $job_vacancy->job_title_id }}
        <small></small>
    </h1>

    <pre>{{ json_encode($job_vacancy, JSON_PRETTY_PRINT) }};</pre>

@stop