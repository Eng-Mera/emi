@extends('layouts.backend')

@section('htmlheader_title')
    Menu Items
@stop

@section('contentheader_title')
    Menu Items
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/restaurant/'.$restaurant_slug.'/menu-item/create') }}"><i class="fa fa-dashboard"></i>Menu Items</a></li>
@endsection

@section('breadcrumb_current')
    List Menu Items
@endsection

@section('main-content')

    <a href="{{ url('admin/restaurant/'.$restaurant_slug.'/menu-item/create') }}" class="btn btn-default">Create New Menu Item</a>
    <hr/>
    <table id="menuitem-datatable" data-slug="{{ $restaurant_slug }}" class="table table-bordered table-striped" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Slug</th>
            <th>Price</th>
            <th>Popular Dish</th>
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
            <th>Slug</th>
            <th>Price</th>
            <th>Popular Dish</th>
            <th>Description</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </tfoot>
    </table>
@stop