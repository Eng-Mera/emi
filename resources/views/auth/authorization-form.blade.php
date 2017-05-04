@extends('layouts.auth')

@section('htmlheader_title')
    Log in
@endsection

@section('content')
    <body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/home') }}"><b>HT</b>R</a>
        </div><!-- /.login-logo -->

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

        <div class="login-box-body">
            {{--<p class="login-box-msg">Validate</p>--}}
            <h2>{{$client->getName()}}</h2>
            <form method="post" action="{{route('oauth.authorize.post', $params)}}">
                {{ csrf_field() }}
                <input type="hidden" name="client_id" value="{{$params['client_id']}}">
                <input type="hidden" name="redirect_uri" value="{{$params['redirect_uri']}}">
                <input type="hidden" name="response_type" value="{{$params['response_type']}}">
                <input type="hidden" name="state" value="{{$params['state']}}">
                <input type="hidden" name="scope" value="{{$params['scope']}}">
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" name="approve" class="btn btn-primary btn-block btn-flat" value="1">
                            Approve
                        </button>
                    </div>
                    <div class="col-xs-6">
                        <button type="submit" name="deny" class="btn btn-primary btn-block btn-flat" value="1">Deny
                        </button>
                    </div>
                </div>
            </form>

        </div><!-- /.login-box-body -->

    </div><!-- /.login-box -->

    @include('layouts.partials.scripts_auth')

    </body>

@endsection
