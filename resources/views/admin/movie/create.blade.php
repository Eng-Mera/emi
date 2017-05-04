@extends('layouts.backend')

@section('htmlheader_title')
    Create New Movie
@endsection

@section('contentheader_title')
    Create New Movie
@endsection

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/movie') }}"><i class="fa fa-dashboard"></i>Movie</a>
    </li>
@endsection

@section('breadcrumb_current')
    Create Movie
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">
            <h1>Create New Movie</h1>

            {!!  Form::model($movie, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'route' => ['admin.movie.store' ]]) !!}
            @include('admin.movie.partials.form-fields')
            <div class="form-group">
                {!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}


        </div>
    </div>
@stop