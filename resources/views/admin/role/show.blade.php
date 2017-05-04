@extends('layouts.backend')

@section('htmlheader_title')
    Role - {{ $role->display_name }}
@endsection

@section('contentheader_title')
    Role {{ $role->display_name }}
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/role') }}"><i class="fa fa-dashboard"></i>Roles</a></li>
@endsection

@section('breadcrumb_current')
    Show Role
@endsection

@section('main-content')
    @if ($role->display_name)
        <h1>{{ $role->display_name }}
            <small>{{ $role->name}}</small>
        </h1>
        <div class="bio">
            <p>
                {{ $role->description}}
            </p>
        </div>
        <hr/>
        {{--<div class="bio">--}}
            {{--<h4>Permissions</h4>--}}
            {{--<ul>--}}
                {{--@foreach($role->perms()->get() as $permission)--}}
                    {{--<li>{{ $permission->display_name }}</li>--}}
                {{--@endforeach--}}
            {{--</ul>--}}
        {{--</div>--}}
        <hr/>
        <div class="bio">
            <h4>Routes</h4>
            <pre>{{ json_encode( $route, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
        </div>

        <a href="{{ URL::to('admin/role/export/'.$role->name) }}"><button class="btn btn-success">Download CSV</button></a>

        <form style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 10px;" action="{{ url('/admin/role/import/'.$role->name) }}" class="form-horizontal" method="post" enctype="multipart/form-data">
            <input type="file" name="import_file" />
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button class="btn btn-primary">Import Roles</button>
        </form>

    @else
        <p>No details found for this role.</p>
    @endif
@stop