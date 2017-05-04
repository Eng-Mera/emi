@extends('layouts.backend')

@section('htmlheader_title')
    Reservations
@stop

@section('contentheader_title')
    Reservations
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/reservation') }}"><i class="fa fa-dashboard"></i>Reservations</a></li>
@endsection

@section('breadcrumb_current')
    List Reservations
@endsection

@section('main-content')

    <hr/>
    <table id="reservation-datatable" class="table table-bordered table-striped" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>ID</th>
            <th>Restaurant</th>
            <th>User</th>
            <th>Status</th>
            <th>Seats</th>
            <th>Time</th>
            <th>Total</th>
            <th>Note</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
        </thead>

        <tfoot>
        <tr>
            <th>ID</th>
            <th>Restaurant</th>
            <th>User</th>
            <th>Status</th>
            <th>Seats</th>
            <th>Time</th>
            <th>Total</th>
            <th>Note</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
        </tfoot>
    </table>

@stop

