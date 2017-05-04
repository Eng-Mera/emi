@extends('layouts.backend')

@section('htmlheader_title')
    Update Job Title
@endsection

@section('contentheader_title')
    Update Job Title
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/job-title') }}"><i class="fa fa-dashboard"></i>Job Title</a></li>
@endsection

@section('breadcrumb_current')
    Update Job Title
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">
            <h1>Job Title</h1>

            {!!  Form::model($jobtitle, ['method' => 'PUT', 'enctype' => 'multipart/form-data', 'route' => ['admin.job-title.update', $jobtitle->id ]]) !!}
            @include('admin.job-title.partials.form-fields')
            <div class="form-group">
                {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}

        </div>
    </div>
@stop