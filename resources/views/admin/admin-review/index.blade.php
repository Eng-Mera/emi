@extends('layouts.backend')

@section('htmlheader_title')
    Admin Reviews
@stop

@section('contentheader_title')
    Admin Reviews
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/admin-review/create') }}"><i class="fa fa-dashboard"></i>Admin
            Reviews</a></li>
@endsection

@section('breadcrumb_current')
    List Admin Reviews
@endsection

@section('main-content')

    <a href="{{ url('admin/admin-review/create') }}" class="btn btn-default">Create New Admin Review</a>
    <hr/>
    <table id="admin-review-datatable" class="table table-bordered table-striped" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Id</th>
            <th>Restaurant Name</th>
            <th>Description</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Id</th>
            <th>Restaurant Name</th>
            <th>Description</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </tfoot>
    </table>
@stop