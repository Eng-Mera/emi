

<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        @if (! Auth::guest())
            <div class="user-panel">
                <div class="pull-left image">
                    @if(isset(Auth::user()->profilePicture))
                        <img src="{{url('file/resize', [160, Auth::user()->profilePicture->filename])}}"
                             alt="User Image"
                             alt="ALT NAME" class="img-circle"/>
                    @else
                        <img src="/img/user2-160x160.jpg" class="user-image" alt="User Image"/>
                    @endif
                </div>
                <div class="pull-left info">
                    <p>{{ Auth::user()->name }}</p>
                    <!-- Status -->
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
        @endif

        {!! $backend_sidebar->asUl(['class' => 'sidebar-menu'], ['class' => 'treeview-menu']) !!}
        
    </section>
    <!-- /.sidebar -->
</aside>
