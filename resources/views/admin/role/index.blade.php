@extends('layouts.backend')

@section('htmlheader_title')
    Roles
@stop

@section('contentheader_title')
    Roles
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/role') }}"><i class="fa fa-dashboard"></i>Roles</a></li>
@endsection

@section('breadcrumb_current')
    List Roles
@endsection

@section('main-content')

    <a href="{{ url('admin/role/create') }}" class="btn btn-default">Create New Role</a>

    <hr/>
    <table id="role-datatable" class="table table-bordered table-striped" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Display Name</th>
            <th>Description</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Display Name</th>
            <th>Description</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </tfoot>
    </table>
@stop