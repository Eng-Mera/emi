@extends('layouts.backend')

@section('htmlheader_title')
    Update Rate
@endsection

@section('contentheader_title')
    Update Rate
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/restaurant/'.$restaurant_slug.'/rates') }}"><i class="fa fa-dashboard"></i>Rates</a></li>
@endsection

@section('breadcrumb_current')
    Update Rate;l
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">
            <h1>Edit Rate</h1>

            {!!  Form::model($rate, ['method' => 'PUT', 'enctype' => 'multipart/form-data', 'route' => ['admin.restaurant.rates.update',  $restaurant_slug, $rate->id ]]) !!}
            @include('admin.rate.partials.form-fields')
            <div class="form-group">
                {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}

        </div>
    </div>
@stop