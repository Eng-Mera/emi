@extends('layouts.backend')

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/permission') }}"><i class="fa fa-dashboard"></i> Permissions</a></li>
@endsection

@section('breadcrumb_current')
    Show Permission
@endsection

@section('contentheader_title')
    Permission {{ $permission->display_name }}
@stop

@section('contentheader_title')
    Permission {{ $permission->display_name }}
@stop

@section('main-content')
    @if ($permission->display_name)
        <h1>{{ $permission->display_name }}
            <small>{{ $permission->name}}</small>
        </h1>
        <div class="bio">
            <p>
                {{ $permission->description}}
            </p>
        </div>
    @else
        <p>No details found for this permission.</p>
    @endif
@stop