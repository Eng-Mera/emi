@extends('layouts.backend')

@section('htmlheader_title')
    Claims
@stop

@section('contentheader_title')
    Claims
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('/admin/claim/') }}"><i class="fa fa-dashboard"></i>Claims</a></li>
@endsection

@section('breadcrumb_current')
    List Claims
@endsection

@section('main-content')

    <table id="claim-datatable"  class="table table-bordered table-striped" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </tfoot>
    </table>
@stop