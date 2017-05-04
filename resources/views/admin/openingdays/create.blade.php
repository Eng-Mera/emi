@extends('layouts.backend')

@section('htmlheader_title')
    Create New Opening day
@endsection

@section('contentheader_title')
    Create New Opening day
@endsection

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/restaurant/'.$restaurant_slug.'/opening-days') }}"><i class="fa fa-dashboard"></i>Opening
            Days</a>
    </li>
@endsection

@section('breadcrumb_current')
    Create Opening Days
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">
            <h1>Opening Day details</h1>

            {!!  Form::model($openingDay, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'route' => ['admin.restaurant.opening-days.store',  $restaurant_slug ]]) !!}
            @include('admin.openingdays.partials.form-fields')
            <div class="form-group">
                {!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}


        </div>
    </div>
@stop