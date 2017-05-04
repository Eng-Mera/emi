@extends('layouts.backend')

@section('htmlheader_title')
    Job Title
@stop

@section('contentheader_title')
    Job Title
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/job-title/create') }}"><i class="fa fa-dashboard"></i>Job Title</a></li>
@endsection

@section('breadcrumb_current')
    List Categories
@endsection

@section('main-content')

    <a href="{{ url('admin/job-title/create') }}" class="btn btn-default">Create New Job Title</a>
    <hr/>

    <table id="job-title-datatable" class="table table-bordered table-striped"
           cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Id</th>
            <th>Job Title</th>
            <th>Description</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Id</th>
            <th>Job Title Name</th>
            <th>Description</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </tfoot>
    </table>
@stop