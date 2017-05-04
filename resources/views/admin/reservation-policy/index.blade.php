@extends('layouts.backend')

@section('htmlheader_title')
    Reservation Policies
@stop

@section('contentheader_title')
    Reservation Policies
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/restaurant/'.$restaurant_slug.'/reservation-policy/create') }}"><i
                    class="fa fa-dashboard"></i>Reservation Policies</a></li>
@endsection

@section('breadcrumb_current')
    List Reservation Policys
@endsection

@section('main-content')

    <a href="{{ url('admin/restaurant/'.$restaurant_slug.'/reservation-policy/create') }}" class="btn btn-default">Create
        New Reservation Policy</a>
    <hr/>
    <table id="reservation-policy-datatable" data-slug="{{ $restaurant_slug }}" class="table table-bordered table-striped"
           cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Start date</th>
            <th>End date</th>
            <th>Status</th>
            <th>Amount</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Start date</th>
            <th>End date</th>
            <th>Status</th>
            <th>Amount</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </tfoot>
    </table>
@stop