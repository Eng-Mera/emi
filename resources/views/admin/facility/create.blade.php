@extends('layouts.backend')

@section('htmlheader_title')
    Create New Facility
@endsection

@section('contentheader_title')
    Create New Facility
@endsection

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/facility/') }}"><i class="fa fa-dashboard"></i>Facilities</a>
    </li>
@endsection

@section('breadcrumb_current')
    Create Facility
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">

            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#facility" aria-controls="home" role="tab" data-toggle="tab">Facility Details</a></li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="facility">
                    @include('admin.facility.partials.create-facility')
                </div>
            </div>

        </div>
    </div>
@stop