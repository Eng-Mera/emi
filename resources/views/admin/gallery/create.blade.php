@extends('layouts.backend')

@section('htmlheader_title')
    Add Gallery Images
@endsection

@section('contentheader_title')
    Add Gallery Images
@endsection

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/restaurant/'.$restaurant_slug.'/gallery') }}"><i class="fa fa-dashboard"></i>Gallery</a>
    </li>
@endsection

@section('breadcrumb_current')
    Create Menu Items
@endsection

@section('main-content')

    <input type="hidden" id="restaurant-slug" data-slug="{{ $restaurant_slug }}">

    <div class="box">
        <div class="box-body">

            <div class="row">
                <div class="container" style="padding-top: 10px;">

                    <div class="row">
                        <div class="col-lg-offset-2 col-lg-8">
                            <div class="page-header">
                                <h1> Add New Gallery Images
                                </h1>
                            </div>
                        </div>

                        @foreach ($locales as $locale)
                            <div class="col-lg-offset-2 col-lg-8">
                                {!! Form::label('I18N['.$locale->lang.'][name]', 'Item Name '.$locale->lang.':') !!}
                                {!! Form::text('I18N['.$locale->lang.'][name]', null, ['class' => 'form-control']) !!}
                                @if($errors->has('I18N['.$locale->lang.'][name]'))
                                    <div class="alert-danger">
                                        {!! $errors->first('I18N['.$locale->lang.'][name]') !!}
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        @foreach ($locales as $locale)
                            <div class="col-lg-offset-2 col-lg-8">
                                {!! Form::label('I18N['.$locale->lang.'][description]', 'Description '.$locale->lang.':') !!}
                                {!! Form::textarea('I18N['.$locale->lang.'][description]', null, ['class' => 'form-control']) !!}
                                @if($errors->has('I18N['.$locale->lang.'][description]'))
                                    <div class="alert-danger">
                                        {!! $errors->first('I18N['.$locale->lang.'][description]') !!}
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        <div class="col-lg-offset-2 col-lg-8">
                            <button type="button" class="btn btn-success" aria-label="Add file" id="add-file-btn">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add file
                            </button>
                            <button type="button" class="btn btn-info" aria-label="Start upload" id="start-upload-btn">
                                <span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Start upload
                            </button>
                            <button type="button" class="btn btn-warning" aria-label="Pause upload"
                                    id="pause-upload-btn">
                                <span class="glyphicon glyphicon-pause " aria-hidden="true"></span> Pause upload
                            </button>
                        </div>


                        <div class="col-lg-offset-2 col-lg-8">
                            <p>
                            <div class="progress hide" id="upload-progress">
                                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                                     style="width: 0%">
                                    <span class="sr-only"></span>
                                </div>
                            </div>
                            </p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@stop