@extends('layouts.backend')

@section('htmlheader_title')
    Update Category
@endsection

@section('contentheader_title')
    Update Category
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/category') }}"><i class="fa fa-dashboard"></i>Categories</a></li>
@endsection

@section('breadcrumb_current')
    Update Category
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">
            <h1>Category</h1>

            {!!  Form::model($category, ['method' => 'PUT', 'enctype' => 'multipart/form-data', 'route' => ['admin.category.update', $category->id ]]) !!}
            @include('admin.category.partials.form-fields')
            <div class="form-group">
                {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}

        </div>
    </div>
@stop