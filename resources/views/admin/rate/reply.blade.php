@extends('layouts.backend')

@section('htmlheader_title')
    Reply on Review
@endsection

@section('contentheader_title')
    Reply on Review
@endsection

@section('breadcrumb_parent')
    <li><a href=""><i class="fa fa-dashboard"></i>Reply</a></li>
@endsection

@section('breadcrumb_current')
    Reply on Review
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">
            {!!  Form::model($reply, ['method' => 'POST', 'route' => ['reply-review.store']]) !!}
            @include('admin.rate.partials.reply-form-fields')
            <div class="form-group">
                {!! Form::hidden('slug', $restaurantSlug) !!}
                {!! Form::hidden('review_id', $id) !!}
                {!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}


        </div>
    </div>
@stop