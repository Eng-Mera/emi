@extends('layouts.backend')

@section('htmlheader_title')
    Update {{ $restaurant->name }}
@endsection

@section('contentheader_title')
    {{ $restaurant->name }}
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/restaurant') }}"><i class="fa fa-dashboard"></i>Restaurant</a></li>
@endsection

@section('breadcrumb_current')
    Update Restaurant
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">
            <h3>Update Restaurant</h3>
            <div class="tab-content">
<!--                --><?php //dd(Auth::user()->hasRole(['restaurant-admin'])); ?>
                @if(!Auth::user()->hasRole(['restaurant-manager']) && Auth::user()->hasRole(['restaurant-admin']))
                <br/>
                <div class="container text-center" style="margin: 0 10%;">

                    <div class="row">
                        <a href=" {{ url('admin/restaurant/'.$restaurant->slug .'/menu-item') }}">
                            <div class="col-sm-6 col-md-4">
                                <div class="thumbnail">
                                    <br>
                                    <div class="grid-icon-center">
                                        <span class="glyphicon glyphicon-list"></span>
                                    </div>
                                    <div class="caption text-center">
                                        <h2>Menu Item</h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href=" {{ url('admin/restaurant/'.$restaurant->slug .'/rates') }}">

                            <div class="col-sm-6 col-md-4">
                                <div class="thumbnail">
                                    <br>
                                    <div class="grid-icon-center">
                                        <span class="glyphicon glyphicon-comment"></span>
                                    </div>
                                    <div class="caption text-center">
                                        <h2>Reviews</h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="row">
                        <a href=" {{ url('admin/restaurant/'.$restaurant->slug .'/opening-day') }}">
                            <div class="col-sm-6 col-md-4">
                                <div class="thumbnail">
                                    <br>
                                    <div class="grid-icon-center">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </div>
                                    <div class="caption text-center">
                                        <h2>Opening Days</h2>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href=" {{ url('admin/restaurant/'.$restaurant->slug .'/job-vacancy') }}">

                            <div class="col-sm-6 col-md-4">
                                <div class="thumbnail">
                                    <br>
                                    <div class="grid-icon-center">
                                        <span class="glyphicon glyphicon-bullhorn"></span>
                                    </div>
                                    <div class="caption text-center">
                                        <h2>Job Vacancies</h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                </div>
                @endif

                @role(['restaurant-manager', 'super-admin'])
                <div class="top-right-arrow">
                    <br/>
                    <a href=" {{ url('admin/restaurant/'.$restaurant->slug .'/menu-item') }}"><span
                                class="glyphicon glyphicon-list"></span> Menu Item</a>
                    -
                    <a href=" {{ url('admin/restaurant/'.$restaurant->slug .'/rates') }}">
                        <span class="glyphicon glyphicon-comment"></span> Reviews</a> -
                    <a href=" {{ url('admin/restaurant/'.$restaurant->slug .'/gallery') }}">
                        <span class="glyphicon glyphicon-picture"></span> Gallery</a> -
                    <a href=" {{ url('admin/restaurant/'.$restaurant->slug .'/branch') }}">
                        <span class="fa fa-code-fork"></span> Branches</a> -
                    <a href=" {{ url('admin/restaurant/'.$restaurant->slug .'/opening-days') }}"><span
                                class="glyphicon glyphicon-calendar"></span> Opening Days</a> -
                    @if($restaurant->allow_job_vacancies)
                    <a href=" {{ url('admin/restaurant/'.$restaurant->slug .'/job-vacancy') }}"><span
                                class="glyphicon glyphicon-bullhorn"></span> Job Vacancies</a> -
                    @endif
                    <a href=" {{ url('admin/restaurant/'.$restaurant->slug .'/reservation-policy') }}"><span
                                class="glyphicon glyphicon-bullhorn"></span> Reservation Policy</a>

                    <hr/>
                </div>
                <div role="tabpanel" class="tab-pane active" id="restaurant">
                    @include('admin.restaurant.partials.edit-restaurant')
                </div>
                @endrole
            </div>

        </div>
    </div>
@stop