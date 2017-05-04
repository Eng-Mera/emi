
<!-- Sidebar Menu -->
<ul class="sidebar-menu">
    <li class="treeview">
        <a href="#"><i class='fa fa-link'></i> <span>Users Management</span> <i
                    class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu ">
            <li><a href="{{ url('admin/user') }}" {!! App\Http\Helpers\ActiveTab::active_class_path(['admin/user']) !!}>Users</a></li>
            <li><a href="{{ url('admin/role') }}" {!! App\Http\Helpers\ActiveTab::active_class_path(['admin/role']) !!}>Roles</a></li>
            <li><a href="{{ url('admin/permission') }}" {!! App\Http\Helpers\ActiveTab::active_class_path(['admin/permission']) !!}>Permissions</a></li>
            <li><a href="{{ url('admin/flush-routes') }}" {!! App\Http\Helpers\ActiveTab::active_class_path(['admin/flush-routes']) !!}>Flush Routes</a></li>

        </ul>
    </li>

    <li class="treeview">
        <a href="#" ><i class='fa fa-link'></i> <span>Reports Management</span> <i
                    class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            <li><a href="{{ url('admin/report') }}" {!! App\Http\Helpers\ActiveTab::active_class_path(['admin/report']) !!}>Reports</a></li>
        </ul>
    </li>

</ul><!-- /.sidebar-menu -->
