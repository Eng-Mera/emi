@extends('layouts.backend')

@section('htmlheader_title')
    User - {{ $adminReview->name }}
@endsection

@section('contentheader_title')
    {{ $adminReview->name }}
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/admin-review') }}"><i class="fa fa-dashboard"></i>Admin Review</a></li>
@endsection

@section('breadcrumb_current')
    Show Admin Review
@endsection

@section('main-content')
        <h1>{{ $adminReview->name }} <small></small></h1>

        <pre>{{ json_encode($adminReview, JSON_PRETTY_PRINT) }};</pre>

@stop