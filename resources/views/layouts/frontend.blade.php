<!DOCTYPE html>
<!--
Landing page based on Pratt: http://blacktie.co/demo/pratt/
-->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pratt - Free Bootstrap 3 Theme">
    <meta name="author" content="Alvarez.is - BlackTie.co">

    <title>HTR - @yield('contentheader_title', 'Page Header here')</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('/css/bootstrap.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('/css/main.css') }}" rel="stylesheet">

    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic' rel='stylesheet'
          type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,300,700' rel='stylesheet' type='text/css'>

    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>
    <!-- Ionicons -->
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css"/>
    <!-- Theme style -->

    <link href="{{ asset('/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css"/>

    <script src="{{ asset('/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('/js/smoothscroll.js') }}"></script>

</head>

<body data-spy="scroll" data-offset="0" data-target="#navigation">

<!-- Fixed navbar -->
<div id="navigation" class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><b>HTR</b></a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">Login</a></li>
                    <li><a href="{{ url('/register') }}">Register</a></li>
                @else
                    <li style="padding-top: 10px;">

                        @if(isset(Auth::user()->profilePicture))
                            <img src="{{url('file/resize', [35, Auth::user()->profilePicture->filename])}}"  alt="ALT NAME" class="img-circle"/>
                        @else
                            <img style="width:35px;" src="/img/user2-160x160.jpg" class="user-image img-circle" alt="User Image"/>
                        @endif

                        <span><a href="{{  url('admin/user/'.Auth::user()->username).'/edit' }}">{{ Auth::user()->name }}</a></span>
                    </li>
                    <li>
                        <a href="{{ url('/logout') }}" class="">{{ trans('Sign out') }}</a>
                    </li>
                @endif
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>

<div id="desc">
    <div class="container" style="background: white;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <small>@yield('contentheader_description')</small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content">
            @if (session('content-message'))
                <div class="alert alert-success">
                    {{ session('content-message') }}
                </div>
                @endif

                        <!-- Your Page Content Here -->
                @yield('main-content')
        </section><!-- /.content -->
    </div> <!--/ .container -->
</div><!--/ #headerwrap -->
@section('scripts')
    @include('layouts.partials.scripts')
@show
</body>
</html>
