@extends('layouts.backend')

@section('htmlheader_title')
    Create New Report
@endsection

@section('contentheader_title')
    Create New Report
@endsection

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/report') }}"><i class="fa fa-dashboard"></i>Reports</a></li>
@endsection

@section('breadcrumb_current')
    Create Report
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">

            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#report" aria-controls="home" role="tab" data-toggle="tab">Report Details</a></li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="report">
                    @include('admin.report.partials.create-report')
                </div>
            </div>

        </div>
    </div>
@stop

