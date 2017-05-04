@extends('layouts.backend')

@section('htmlheader_title')
    Create New Branch
@endsection

@section('contentheader_title')
    Create New Branch
@endsection

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/restaurant/'.$restaurant_slug.'/branch') }}"><i class="fa fa-dashboard"></i>Branch</a>
    </li>
@endsection

@section('breadcrumb_current')
    Create New Branch
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">

            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#branch" aria-controls="home" role="tab" data-toggle="tab">Branch Details</a></li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="branch">
                    @include('admin.branch.partials.create-branch')
                </div>
            </div>

        </div>
    </div>
@stop