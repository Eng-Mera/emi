@extends('layouts.backend')

@section('htmlheader_title')
    Create New User
@endsection

@section('contentheader_title')
    Create New User
@endsection

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/user') }}"><i class="fa fa-dashboard"></i>Users</a></li>
@endsection

@section('breadcrumb_current')
    Create User
@endsection

@section('main-content')


    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="register-box-body">
        <form action="{{ url('/admin/user') }}" method="post" enctype="multipart/form-data">

            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="Full name" name="name"
                       value="{{ old('name') }}"/>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="Username" name="username"
                       value="{{ old('username') }}"/>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback">
                <input type="text" class="form-control" data-provide="datepicker" placeholder="Date of birth"
                       name="dob" value="{{ old('dob') }}"/>
                <span class="glyphicon glyphicon-time form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="Mobile" name="mobile" value="{{ old('mobile') }}"/>
                <span class="glyphicon glyphicon-time form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback">
                <select name="gender" class="form-control">
                    <option value="">Select Gender</option>
                    <option value="1">Male</option>
                    <option value="2">Female</option>
                </select>
                <span class="glyphicon glyphicon-time form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback">
                <input type="email" class="form-control" placeholder="Email" name="email"
                       value="{{ old('email') }}"/>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Password" name="password"/>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Retype password"
                       name="password_confirmation"/>
                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            </div>

            <?php $user = new \App\User(); ?>

            @include('admin.user.partials.add-user-role-permissions')

            <div class="form-group has-feedback">
                <label>Profile Picture</label>
                <br/>
                @if(Request::input('social'))
                    <img src="{{ old('uploaded_file') }}"/>
                @else
                    <input type="file" class="form-control" name="uploaded_file"/>
                    <span class="glyphicon glyphicon-picture form-control-feedback"></span>
                @endif
            </div>

            <div class="row">
                <div class="col-xs-2">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
                </div><!-- /.col -->
            </div>

        </form>

    </div><!-- /.form-box -->

    @include('layouts.partials.scripts_auth')


@endsection
