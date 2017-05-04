@extends('layouts.backend')

@section('htmlheader_title')
    Create New Reservation Policy
@endsection

@section('contentheader_title')
    Create New Reservation Policy
@endsection

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/restaurant/'.$restaurant_slug.'/reservation-policy') }}"><i class="fa fa-dashboard"></i>Reservation Policys</a>
    </li>
@endsection

@section('breadcrumb_current')
    Create Reservation Policys
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">

            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#restaurant" aria-controls="home" role="tab" data-toggle="tab">Restaurant Details</a></li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="restaurant">
                    @include('admin.reservation-policy.partials.create-reservation-policy')
                </div>
            </div>

        </div>
    </div>
@stop