@extends('layouts.backend')

@section('htmlheader_title')
    Users
@stop

@section('contentheader_title')
    Users
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/user') }}"><i class="fa fa-dashboard"></i>Users</a></li>
@endsection

@section('breadcrumb_current')
    List Users
@endsection

@section('main-content')

    <a href="{{ url('admin/user/create') }}" class="btn btn-default">Create New User</a>

    @if(Auth::user()->hasRole(\App\Role::SUPER_ADMIN))
    <h3>Filters</h3>
    <table border="0" cellspacing="5" cellpadding="5">
        <tbody>
        <tr>
            <td>
                <div class="form-group">
                    {!! Form::select('role', ['' => 'Select Role ..'] + array_pluck(\App\Role::all()->toArray(), 'display_name', 'name'),null, [ 'class' => 'form-control', 'id' => 'role-filter']) !!}
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    @endif
    <hr/>

    <table id="users-datatable" class="table table-bordered table-striped" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Date of birth</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Date of birth</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </tfoot>
    </table>
@stop