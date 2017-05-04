@extends('layouts.frontend')

@section('contentheader_title')
    {{ $user->name }}
@stop

@section('main-content')
    <div class="box">
        <div class="box-body">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#user" aria-controls="home" role="tab"
                                                          data-toggle="tab">Personal Information</a></li>
                <li role="presentation"><a href="#profile" aria-controls="profile" role="tab"
                                           data-toggle="tab">Profile</a></li>
                <li role="presentation"><a href="#picture" aria-controls="profile" role="tab"
                                           data-toggle="tab">Picture</a></li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="user">
                    @include('profile.partials.edit-user')
                </div>
                <div role="tabpanel" class="tab-pane" id="profile">
                    @include('profile.partials.edit-profile')
                </div>
                <div role="tabpanel" class="tab-pane" id="picture">
                    @include('profile.partials.edit-photo')
                </div>
            </div>

        </div>
    </div>
@stop
