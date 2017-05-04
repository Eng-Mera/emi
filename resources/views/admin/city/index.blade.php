@extends('layouts.backend')

@section('htmlheader_title')
    City
@stop

@section('contentheader_title')
    City
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/city//create') }}"><i class="fa fa-dashboard"></i>Cities</a></li>
@endsection

@section('breadcrumb_current')
    List Cities
@endsection

@section('main-content')

    <a href="{{ url('admin/city/create') }}" class="btn btn-default">Create New City</a>
    <hr/>

    <table id="city-datatable" class="table table-bordered table-striped"
           cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Id</th>
            <th>City Name</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Id</th>
            <th>City Name</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </tfoot>
    </table>
@stop