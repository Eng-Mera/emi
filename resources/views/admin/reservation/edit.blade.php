@extends('layouts.backend')

@section('htmlheader_title')
    Change Reservation #{{ $reservation->id }}
@endsection

@section('contentheader_title')
    Change Reservation #{{ $reservation->id }}
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/reservation') }}"><i class="fa fa-dashboard"></i>Reservation</a></li>
@endsection

@section('breadcrumb_current')
    Change Reservation
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">

            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#menuitem" aria-controls="home" role="tab" data-toggle="tab">Reservation Details</a></li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="menuitem">
                    @include('admin.reservation.partials.edit-reservation')
                </div>
            </div>

        </div>
    </div>
@stop