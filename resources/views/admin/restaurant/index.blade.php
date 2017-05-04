@extends('layouts.backend')

@section('htmlheader_title')
    Restaurants
@stop

@section('contentheader_title')
    Restaurants
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/user') }}"><i class="fa fa-dashboard"></i>Restaurants</a></li>
@endsection

@section('breadcrumb_current')
    List Restaurants
@endsection

@section('main-content')

    <a href="{{ url('admin/restaurant/create') }}" class="btn btn-default">Create New Restaurant</a>
    <hr/>
    <table id="restaurant-datatable" class="table table-bordered table-striped" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Slug</th>
            <th>Email</th>
            <th>Owner</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Slug</th>
            <th>Email</th>
            <th>Owner</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </tfoot>
    </table>
@stop