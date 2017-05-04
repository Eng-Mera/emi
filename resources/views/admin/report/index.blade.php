@extends('layouts.backend')

@section('htmlheader_title')
    Reports
@stop

@section('contentheader_title')
    Reports
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/report') }}"><i class="fa fa-dashboard"></i>Reports</a></li>
@endsection

@section('breadcrumb_current')
    List Reports
@endsection

@section('main-content')

    {{--<a href="{{ url('admin/report/create') }}" class="btn btn-default">Create New Report</a>--}}
    <hr/>

    <table border="0" cellspacing="5" cellpadding="5">
        <tbody>
        <tr>
            <td>
                <div class="form-group">
                    {!! Form::select('report_type', ['' => 'Select Type ..'] + \App\Report::getReportTypes(),null, [
                    'class' => 'form-control', 'id' => 'type-filter']) !!}
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    <hr/>
    <table id="report-datatable" class="table table-bordered table-striped" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Subject</th>
            <th>Reported ID</th>
            <th>User ID</th>
            <th>Details</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </thead>
        
        <tfoot>
        <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Subject</th>
            <th>Reported ID</th>
            <th>User ID</th>
            <th>Details</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </tfoot>
    </table>
    
@stop

