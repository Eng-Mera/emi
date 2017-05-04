@extends('layouts.backend')

@section('htmlheader_title')
    Update {{ $reservationPolicy->name }}
@endsection

@section('contentheader_title')
    {{ $reservationPolicy->name }}
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/restaurant/'.$restaurant_slug.'/reservation-policy') }}"><i class="fa fa-dashboard"></i>Reservation Policy</a></li>
@endsection

@section('breadcrumb_current')
    Update Reservation Policy
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">

            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#menuitem" aria-controls="home" role="tab" data-toggle="tab">Reservation Policy Details</a></li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="menuitem">
                    @include('admin.reservation-policy.partials.edit-reservation-policy')
                </div>
             </div>

        </div>
    </div>
@stop