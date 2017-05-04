@extends('layouts.backend')

@section('htmlheader_title')
    Update City
@endsection

@section('contentheader_title')
    Update City
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/city') }}"><i class="fa fa-dashboard"></i>Cities</a></li>
@endsection

@section('breadcrumb_current')
    Update City
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">
            <h1>City</h1>

            {!!  Form::model($city, ['method' => 'PUT', 'enctype' => 'multipart/form-data', 'route' => ['admin.city.update', $city->id ]]) !!}
            @include('admin.city.partials.form-fields')
            <div class="form-group">
                {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}

        </div>
    </div>
@stop