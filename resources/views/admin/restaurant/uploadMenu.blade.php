@extends('layouts.backend')

@section('htmlheader_title')
    Restaurants
@stop

@section('contentheader_title')
    Restaurants
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/user') }}"><i class="fa fa-dashboard"></i>Restaurants</a></li>
@endsection

@section('breadcrumb_current')
    List Restaurants
@endsection

@section('main-content')

    <a href="" class="btn btn-default">Upload Restaurants</a>
    <hr/>
    <a href="{{ URL::to('downloadExcel/xls') }}"><button class="btn btn-success">Download Excel xls</button></a>
    <a href="{{ URL::to('downloadExcel/xlsx') }}"><button class="btn btn-success">Download Excel xlsx</button></a>
    <a href="{{ URL::to('downloadExcel/csv') }}"><button class="btn btn-success">Download CSV</button></a>
    <form style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 10px;" action="{{ url('/admin/importItems') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
        <input type="file" name="import_file" />
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button class="btn btn-primary">Import File</button>
    </form>
@stop