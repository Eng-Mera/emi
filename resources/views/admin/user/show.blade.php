@extends('layouts.backend')

@section('htmlheader_title')
    User - {{ $user->name }}
@endsection

@section('contentheader_title')
    {{ $user->name }}
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/user') }}"><i class="fa fa-dashboard"></i>Users</a></li>
@endsection

@section('breadcrumb_current')
    Show User
@endsection

@section('main-content')
    @if ($user)
        <h1>{{ $user->name }}
            <small>{{ $user->profile->location }}</small>
        </h1>
        <div class="bio">

            <pre>{{ json_encode($user, JSON_PRETTY_PRINT) }}</pre>

        </div>

    @else
        <p>No profile yet.</p>
    @endif
@stop