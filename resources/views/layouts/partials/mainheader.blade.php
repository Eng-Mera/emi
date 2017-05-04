<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="{{ url('/home') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>HT</b>R</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>HT</b>R</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">Login</a></li>
                    <li><a href="{{ url('/register') }}">Register</a></li>
                @else
                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            @if(isset(Auth::user()->profilePicture))
                            <img src="{{url('file/resize', [45, Auth::user()->profilePicture->filename])}}" alt="User Image" alt="ALT NAME" class="user-image"/>
                            @else
                            <img src="{{ asset('/img/user2-160x160.jpg') }}" class="user-image" alt="User Image"/>
                            @endif
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                @if( isset(Auth::user()->profilePicture))
                                    <img src="{{url('file/resize', [160, Auth::user()->profilePicture->filename])}}" alt="User Image" alt="ALT NAME" class="img-circle"/>
                                @else
                                    <img src="{{ asset('/img/user2-160x160.jpg') }}" class="user-image" alt="User Image"/>
                                @endif
                                <p>
                                    {{ Auth::user()->name }}
                                    <small>Member since Nov. 2012</small>
                                </p>
                            </li>

                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="{{ url('admin/user',[Auth::user()->username, 'edit']) }}" class="btn btn-default btn-flat">Profile</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{ url('/logout') }}" class="btn btn-default btn-flat">Sign out</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                @endif

            </ul>
        </div>
    </nav>
</header>