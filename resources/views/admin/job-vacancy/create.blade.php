@extends('layouts.backend')

@section('htmlheader_title')
    Create New Job Vacancy
@endsection

@section('contentheader_title')
    Create New Job Vacancy
@endsection

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/restaurant/'. $slug .'/job-vacancy') }}"><i class="fa fa-dashboard"></i>Job Vacancy</a>
    </li>
@endsection

@section('breadcrumb_current')
    Create Job Vacancy
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">
            <h1>Create New Job Vacancy</h1>

            {!!  Form::model($jobVacancy, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'route' => ['admin.restaurant.job-vacancy.store', $slug]]) !!}
            @include('admin.job-vacancy.partials.form-fields')
            <div class="form-group">
                {!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}


        </div>
    </div>
@stop