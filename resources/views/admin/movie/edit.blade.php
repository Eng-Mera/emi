@extends('layouts.backend')

@section('htmlheader_title')
    Update Movie
@endsection

@section('contentheader_title')
    Update Movie
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/movie') }}"><i class="fa fa-dashboard"></i>Movies</a></li>
@endsection

@section('breadcrumb_current')
    Update Movie
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">
            <h1>Movie</h1>

            {!!  Form::model($movie, ['method' => 'PUT', 'enctype' => 'multipart/form-data', 'route' => ['admin.movie.update', $movie->id ]]) !!}
            @include('admin.movie.partials.form-fields')
            <div class="form-group">
                {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}

        </div>
    </div>
@stop