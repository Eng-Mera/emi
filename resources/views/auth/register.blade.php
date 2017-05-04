@extends('layouts.auth')

@section('htmlheader_title')
    Register
@endsection

@section('content')

    <body class="hold-transition register-page">
    <div class="register-box">
        <div class="register-logo">
            <a href="{{ url('/home') }}"><b>HT</b>R</a>
        </div>

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
            {{--<p class="login-box-msg">Register a new membership</p>--}}
            <form action="{{ url('/register') }}" method="post">

                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group has-feedback">
                    <div class="radio form-control">
                        <label>
                            <input type="radio" name="pending" id="restaurant-manager" value="restaurant-manager">
                            Restaurant Manager
                        </label>
                        <label>
                            <input type="radio" name="pending" id="job-seeker" value="job-seeker">
                            Job Seeker
                        </label>
                    </div>

                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>

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
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox"> I agree to the <a href="#">terms</a>
                            </label>
                        </div>
                    </div><!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
                    </div><!-- /.col -->
                </div>

                @if(Request::input('social'))
                    @include('auth.partials.social_fields')
                @endif

            </form>

            @if(!Request::input('social'))
                @include('auth.partials.social_login')
            @endif

            <a href="{{ url('/login') }}" class="text-center">I already have a membership</a>
        </div><!-- /.form-box -->
    </div><!-- /.register-box -->

    @include('layouts.partials.scripts_auth')

    </body>

@endsection
