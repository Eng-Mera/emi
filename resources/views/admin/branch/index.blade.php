@extends('layouts.backend')

@section('htmlheader_title')
    Restaurant Branches
@stop

@section('contentheader_title')
    Restaurant Branches
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/restaurant/'.$restaurant_slug.'/branch/create') }}"><i class="fa fa-dashboard"></i>Branches</a></li>
@endsection

@section('breadcrumb_current')
    List Restaurant Branches
@endsection

@section('main-content')

    <a href="{{ url('admin/restaurant/'.$restaurant_slug.'/branch/create') }}" class="btn btn-default">Create New Branch</a>
    <hr/>
    <table id="branch-datatable" data-slug="{{ $restaurant_slug }}" class="table table-bordered table-striped" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Id</th>
            <th>Restaurant</th>
            <th>Address</th>
            <th>Slug</th>
            <th>Latitude</th>
            <th>Longitude</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Id</th>
            <th>Restaurant</th>
            <th>Address</th>
            <th>Slug</th>
            <th>Latitude</th>
            <th>Longitude</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </tfoot>
    </table>
@stop