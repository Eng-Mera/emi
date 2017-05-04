@extends('layouts.backend')

@section('htmlheader_title')
    Opening Days
@stop

@section('contentheader_title')
    Opening Days
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/restaurant/'.$restaurant_slug.'/opening-days/create') }}"><i class="fa fa-dashboard"></i>Opening
            Days</a></li>
@endsection

@section('breadcrumb_current')
    List Opening Days
@endsection

@section('main-content')

    <a href="{{ url('admin/restaurant/'.$restaurant_slug.'/opening-days/create') }}" class="btn btn-default">Create New Opening Days</a>
    <hr/>

    <table id="opening-days-datatable" data-slug="{{ $restaurant_slug }}" class="table table-bordered table-striped"
           cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Id</th>
            <th>Open Day</th>
            <th>From</th>
            <th>To</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Id</th>
            <th>Open Day</th>
            <th>From</th>
            <th>To</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </tfoot>
    </table>
@stop