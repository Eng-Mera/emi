@extends('layouts.backend')

@section('htmlheader_title')
    Reply
@endsection

@section('contentheader_title')
    Reply
@stop

@section('breadcrumb_parent')
    <li><a href=""><i class="fa fa-dashboard"></i>Reviews</a></li>
@endsection

@section('breadcrumb_current')
    Show Reply
@endsection

@section('main-content')

    <pre>{{ json_encode($reply, JSON_PRETTY_PRINT) }};</pre>

    <a title="Edit" href="{{ url('admin/reply-review/'.$review_id.'/'.$id.'/edit') }}" class="btn btn-primary">Edit</a>
    <form action="{{ url('admin/reply-review/'.$review_id.'/'.$id.'') }}" method="POST">
        <input type="hidden" name="_method" value="DELETE"/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button class="btn btn-danger">Delete</button>
    </form>

@stop