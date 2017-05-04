@extends('layouts.backend')

@section('htmlheader_title')
    Update Opening day
@endsection

@section('contentheader_title')
    Update Opening day
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/restaurant/'.$restaurant_slug.'/opening-days') }}"><i class="fa fa-dashboard"></i>Opening days</a></li>
@endsection

@section('breadcrumb_current')
    Update Menu Item
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">
            <h1>Opening Day details</h1>

            {!!  Form::model($openingDay, ['method' => 'PUT', 'enctype' => 'multipart/form-data', 'route' => ['admin.restaurant.opening-days.update',  $restaurant_slug, $openingDay->id ]]) !!}
            @include('admin.openingdays.partials.form-fields')
            <div class="form-group">
                {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
                </div>
            {!! Form::close() !!}

        </div>
    </div>
@stop