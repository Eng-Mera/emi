@extends('layouts.backend')

@section('htmlheader_title')
    Create New City
@endsection

@section('contentheader_title')
    Create New City
@endsection

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/city') }}"><i class="fa fa-dashboard"></i>City</a>
    </li>
@endsection

@section('breadcrumb_current')
    Create City
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">
            <h1>Create New City</h1>

            {!!  Form::model($city, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'route' => ['admin.city.store' ]]) !!}
            @include('admin.city.partials.form-fields')
            <div class="form-group">
                {!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}


        </div>
    </div>
@stop