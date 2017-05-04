@extends('layouts.backend')

@section('htmlheader_title')
    Facilities
@stop

@section('contentheader_title')
    Facilities
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/facility/') }}"><i class="fa fa-dashboard"></i>Facilities</a></li>
@endsection

@section('breadcrumb_current')
    List Facilities
@endsection

@section('main-content')

    <a href="{{ url('admin/facility/create') }}" class="btn btn-default">Create New Facility</a>
    <hr/>
    <table id="facility-datatable"  class="table table-bordered table-striped" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Description</th>
            <th>Icon</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Description</th>
            <th>Icon</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </tfoot>
    </table>
@stop