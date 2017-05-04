@extends('layouts.backend')

@section('htmlheader_title')
    Update Admin Review
@endsection

@section('contentheader_title')
    Update Admin Review
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/admin-review') }}"><i class="fa fa-dashboard"></i>Admin Reviews</a></li>
@endsection

@section('breadcrumb_current')
    Update Admin Review
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">
            <h1>Admin Review</h1>

            {!!  Form::model($adminReview, ['method' => 'PUT', 'enctype' => 'multipart/form-data', 'route' => ['admin.admin-review.update', $adminReview->id ]]) !!}
            @include('admin.admin-review.partials.form-fields')
            <div class="form-group">
                {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}

        </div>
    </div>
@stop