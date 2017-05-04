@extends('layouts.backend')

@section('htmlheader_title')
    Update Role - {{ $role->display_name }}
@endsection

@section('contentheader_title')
    Update Role - {{ $role->display_name }}
@endsection

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/role') }}"><i class="fa fa-dashboard"></i>Roles</a></li>
@endsection

@section('breadcrumb_current')
    Update Role
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
        <form action="{{ url('/admin/role/'. $role->name) }}" method="POST">
            <input type="hidden" name="_method" value="PATCH">
            @include('admin.role.partials.form', ['action' => 'Update'])
        </form>
    </div><!-- /.form-box -->

    @include('layouts.partials.scripts_auth')

@endsection
