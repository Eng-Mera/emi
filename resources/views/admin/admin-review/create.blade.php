@extends('layouts.backend')

@section('htmlheader_title')
    Create New Admin Review
@endsection

@section('contentheader_title')
    Create New Admin Review
@endsection

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/admin-review') }}"><i class="fa fa-dashboard"></i>Admin Review</a>
    </li>
@endsection

@section('breadcrumb_current')
    Create Admin Review
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">
            <h1>Create New Admin Review</h1>

            {!!  Form::model($adminReview, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'route' => ['admin.admin-review.store' ]]) !!}
            @include('admin.admin-review.partials.form-fields')
            <div class="form-group">
                {!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}


        </div>
    </div>
@stop