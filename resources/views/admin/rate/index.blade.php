@extends('layouts.backend')

@section('htmlheader_title')
    Reviews
@stop

@section('contentheader_title')
    Reviews
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/restaurant/'.$restaurant_slug.'/rates/create') }}"><i class="fa fa-dashboard"></i>Rates &
            Reviews</a></li>
@endsection

@section('breadcrumb_current')
    List Reviews
@endsection

@section('main-content')

    {{--<a href="{{ url('admin/restaurant/'.$restaurant_slug.'/rates/create') }}" class="btn btn-default">Create New--}}
        {{--Review</a>--}}
    {{--<hr/>--}}

    <table id="rate-review-datatable" data-slug="{{ $restaurant_slug }}" class="table table-bordered table-striped"
           cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Id</th>
            <th>Title</th>
            {{--<th>Rate</th>--}}
            {{--<th>Type</th>--}}
            <th>Description</th>
            <th>Created</th>
            <th>Updated</th>
            <th>Action</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Id</th>
            {{--<th>Rate</th>--}}
            {{--<th>Type</th>--}}
            <th>Title</th>
            <th>Description</th>
            <th>Created</th>
            <th>Updated</th>
            <th>Action</th>
        </tr>
        </tfoot>
    </table>
@stop