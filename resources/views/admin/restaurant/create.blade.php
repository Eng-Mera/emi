@extends('layouts.backend')

@section('htmlheader_title')
    Create New User
@endsection

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/user') }}"><i class="fa fa-dashboard"></i>Users</a></li>
@endsection

@section('breadcrumb_current')
    Create User
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">
            <h3>Create User</h3>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="restaurant">
                    @include('admin.restaurant.partials.create-restaurant')
                </div>
            </div>

        </div>
    </div>
@stop