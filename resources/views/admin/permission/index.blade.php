@extends('layouts.backend')

@section('htmlheader_title')
    Permissions
@stop

@section('contentheader_title')
    Permissions
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/permission') }}"><i class="fa fa-dashboard"></i> Permissions</a></li>
@endsection

@section('breadcrumb_current')
    List Permissions
@endsection

@section('main-content')

    <a href="{{ url('admin/permission/create') }}" class="btn btn-default">Create New Permission</a>

    <hr/>
    <table id="permission-datatable" class="table table-bordered table-striped" cellspacing="0" width="100%">
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