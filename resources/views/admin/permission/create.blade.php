@extends('layouts.backend')

@section('htmlheader_title')
    Create New Permission
@endsection

@section('contentheader_title')
    Create New Permission
@endsection

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/permission') }}"><i class="fa fa-dashboard"></i> Permissions</a></li>
@endsection

@section('breadcrumb_current')
    Create Permission
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
        <form action="{{ url('/admin/permission') }}" method="post">

            @include('admin.permission.partials.form',['action' => 'Create'])

        </form>

    </div><!-- /.form-box -->

    @include('layouts.partials.scripts_auth')

@endsection
