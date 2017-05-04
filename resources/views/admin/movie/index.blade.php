@extends('layouts.backend')

@section('htmlheader_title')
    Movie
@stop

@section('contentheader_title')
    Movie
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/movie//create') }}"><i class="fa fa-dashboard"></i>Movie</a></li>
@endsection

@section('breadcrumb_current')
    List Movies
@endsection

@section('main-content')

    <a href="{{ url('admin/movie/create') }}" class="btn btn-default">Create New Movie</a>
    <hr/>

    <table id="movie-datatable" class="table table-bordered table-striped"
           cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Id</th>
            <th>Movie Name</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Id</th>
            <th>Movie Name</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        </tfoot>
    </table>
@stop