@extends('layouts.backend')

@section('htmlheader_title')
    Update Reply
@endsection

@section('contentheader_title')
    Update Reply
@stop

@section('breadcrumb_parent')
    <li><a href=""><i class="fa fa-dashboard"></i>Reviews</a></li>
@endsection

@section('breadcrumb_current')
    Update Reply
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">
            <h1>Edit Reply</h1>

            {!!  Form::model($reply, ['method' => 'PATCH', 'enctype' => 'multipart/form-data', 'route' => ['reply-review.update']]) !!}
            @include('admin.rate.partials.reply-form-fields')
            <div class="form-group">
                {!! Form::hidden('id', $id) !!}
                {!! Form::hidden('review_id', $review_id) !!}
                {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}

        </div>
    </div>
@stop