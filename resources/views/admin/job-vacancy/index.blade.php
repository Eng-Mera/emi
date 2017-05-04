@extends('layouts.backend')

@section('htmlheader_title')
    Job Vacancy
@stop

@section('contentheader_title')
    Job Vacancies
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/restaurant/'. $slug .'/job-vacancy/create') }}"><i class="fa fa-dashboard"></i>Job Vacancy</a></li>
@endsection

@section('breadcrumb_current')
    List Job Vacancies
@endsection

@section('main-content')

    <a href="{{ url('admin/restaurant/'. $slug .'/job-vacancy/create') }}" class="btn btn-default">Create New Job Vacancy</a>
    <hr/>

    <table id="job-vacancy-datatable" data-slug="{{ $slug }}" class="table table-bordered table-striped"
           cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Id</th>
            <th>Job Name</th>
            <th>Status</th>
            <th>Description</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Id</th>
            <th>Job Name</th>
            <th>Status</th>
            <th>Description</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </tfoot>
    </table>
@stop