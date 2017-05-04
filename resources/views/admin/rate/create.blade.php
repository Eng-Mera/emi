@extends('layouts.backend')

@section('htmlheader_title')
    Create Rate
@endsection

@section('contentheader_title')
    Create Rate
@endsection

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/restaurant/'.$restaurant_slug.'/rates') }}"><i class="fa fa-dashboard"></i>Rates</a></li>
@endsection

@section('breadcrumb_current')
    Create Rate
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">
            {!!  Form::model($review, ['method' => 'POST', 'route' => ['admin.restaurant.rates.store',  $restaurant_slug ]]) !!}
            @include('admin.rate.partials.form-fields')
            <div class="form-group">
                {!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}


        </div>
    </div>
@stop