@extends('layouts.backend')

@section('htmlheader_title')
    Update {{ $user->name }}
@endsection

@section('contentheader_title')
    {{ $user->name }}
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/user') }}"><i class="fa fa-dashboard"></i>Users</a></li>
@endsection

@section('breadcrumb_current')
    Update User
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">

            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#user" aria-controls="home" role="tab" data-toggle="tab">Personal Information</a></li>
                <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a></li>
                <li role="presentation"><a href="#picture" aria-controls="profile" role="tab" data-toggle="tab">Picture</a></li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="user">
                    @include('admin.user.partials.edit-user')
                </div>
                <div role="tabpanel" class="tab-pane" id="profile">
                    @include('admin.user.partials.edit-profile')
                </div>
                <div role="tabpanel" class="tab-pane" id="picture">
                    @include('admin.user.partials.edit-photo')
                </div>
            </div>

        </div>
    </div>
@stop
