@extends('layouts.backend')

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/permission') }}"><i class="fa fa-dashboard"></i> Permissions</a></li>
@endsection

@section('breadcrumb_current')
    Edit Permission
@endsection

@section('htmlheader_title')
    Update Permission - {{ $permission->display_name }}
@endsection

@section('contentheader_title')
    Update Permission - {{ $permission->display_name }}
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
        <form action="{{ url('/admin/permission/'. $permission->name) }}" method="POST">
            <input type="hidden" name="_method" value="PATCH">
            @include('admin.permission.partials.form', ['action' => 'Update'])
        </form>

    </div><!-- /.form-box -->

    @include('layouts.partials.scripts_auth')

@endsection
