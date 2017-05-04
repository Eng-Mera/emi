@extends('layouts.backend')

@section('htmlheader_title')
    Category
@stop

@section('contentheader_title')
    Category
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/category//create') }}"><i class="fa fa-dashboard"></i>Category</a></li>
@endsection

@section('breadcrumb_current')
    List Categories
@endsection

@section('main-content')

    <a href="{{ url('admin/category/create') }}" class="btn btn-default">Create New Category</a>
    <hr/>

    <table id="category-datatable" class="table table-bordered table-striped"
           cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Id</th>
            <th>Category Name</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Id</th>
            <th>Category Name</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </tfoot>
    </table>
@stop