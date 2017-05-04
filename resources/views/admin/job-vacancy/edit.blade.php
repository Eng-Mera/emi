@extends('layouts.backend')

@section('htmlheader_title')
    Update Job Vacancy
@endsection

@section('contentheader_title')
    Update Job Vacancy
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/restaurant/'. $slug .'/job-vacancy') }}"><i class="fa fa-dashboard"></i>Job Vacancy</a></li>
@endsection

@section('breadcrumb_current')
    Update Job Vacancy
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">
            <h1>Job Vacancy</h1>

            {!!  Form::model($jobVacancy, ['method' => 'PUT', 'enctype' => 'multipart/form-data', 'route' => ['admin.restaurant.job-vacancy.update', $slug,$jobVacancy->id ]]) !!}
            @include('admin.job-vacancy.partials.form-fields')
            <div class="form-group">
                {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}

        </div>
    </div>
@stop