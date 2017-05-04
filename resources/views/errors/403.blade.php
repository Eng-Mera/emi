@extends('layouts.backend')

@section('htmlheader_title')
    Forbidden
@endsection

@section('contentheader_title')
    403 Error Page
@endsection

@section('$contentheader_description')
@endsection

@section('main-content')

    <div class="error-page">
        <h2 class="headline text-yellow"> 403</h2>
        <div class="error-content">
            <h3><i class="fa fa-warning text-yellow"></i> Oops! Forbidden.</h3>
            <p>
                You arn't allowed to open this page.
            </p>
        </div><!-- /.error-content -->
    </div><!-- /.error-page -->
@endsection