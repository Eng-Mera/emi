@extends('layouts.backend')

@section('htmlheader_title')
    Create New Category
@endsection

@section('contentheader_title')
    Create New Category
@endsection

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/dish-category') }}"><i class="fa fa-dashboard"></i>Dish Category</a>
    </li>
@endsection

@section('breadcrumb_current')
    Create Category
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">
            <h1>Create New Category</h1>

            {!!  Form::model($category, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'route' => ['admin.dish-category.store' ]]) !!}
            @include('admin.category.partials.form-fields')
            <div class="form-group">
                {!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}


        </div>
    </div>
@stop