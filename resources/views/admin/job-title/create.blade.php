@extends('layouts.backend')

@section('htmlheader_title')
    Create New Job Title
@endsection

@section('contentheader_title')
    Create New Job Title
@endsection

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/jobtitle') }}"><i class="fa fa-dashboard"></i>Job Title</a>
    </li>
@endsection

@section('breadcrumb_current')
    Create Job Title
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">
            <h1>Create New Job Title</h1>

            {!!  Form::model($jobtitle, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'route' => ['admin.job-title.store' ]]) !!}
            @include('admin.job-title.partials.form-fields')
            <div class="form-group">
                {!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}


        </div>
    </div>
@stop