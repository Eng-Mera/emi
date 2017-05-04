@extends('layouts.frontend')

@section('contentheader_title')
    {{ $user->name }}
@stop

@section('main-content')
    @if ($user->profile)
        <h1>{{ $user->username }} <small>{{ $user->profile->location }}</small></h1>
        <div class="bio">
            <p>
                {{ $user->profile->bio }}
            </p>
        </div>

        <ul class="links">
            <li>{{ url('http://twitter.com/' . $user->profile->twitter_username, 'Find Me On Twitter') }}</li>
            <li>{{ url('http://github.com/' . $user->profile->github_username, 'View My Work On GitHub') }}</li>
        </ul>

        @if (Auth::user() && Auth::user()->id == $user->id)
            <a href="{{ url('admin/user/edit', $user->username) }}">{{ trans('Edit Profile') }}</a>
        @endif
    @else
        <p>No profile yet.</p>
    @endif
@stop