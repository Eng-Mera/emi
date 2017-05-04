@extends('layouts.backend')

@section('htmlheader_title')
    Approve Claims
@endsection

@section('contentheader_title')
    Approve Claims
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/claim/') }}"><i class="fa fa-dashboard"></i>Claims</a></li>
@endsection

@section('breadcrumb_current')
    Approve Claims
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">

            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#claims" aria-controls="home" role="tab" data-toggle="tab">Claims</a></li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="claims">
                    @include('admin.claim.partials.approve-claim')
                </div>
            </div>

        </div>
    </div>
@stop