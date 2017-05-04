<h1>Profile</h1>

{!!  Form::model($user->profile, ['method' => 'PATCH', 'route' => ['profile.update', $user->username]]) !!}

<div class="form-group">
    {!! Form::label('location', 'Location:') !!}
    {!! Form::text('location', null, ['class' => 'form-control']) !!}
    @if($errors->has('location'))
        <div class="alert-danger">
            {!! $errors->first('location') !!}
        </div>
    @endif
</div>

<!-- Bio Field -->
<div class="form-group">
    {!! Form::label('bio', 'Bio:') !!}
    {!! Form::textarea('bio', null, ['class' => 'form-control']) !!}
    @if($errors->has('bio'))
        <div class="alert-danger">
            {!! $errors->first('bio') !!}
        </div>
    @endif
</div>

<!-- Twitter_username Field -->
<div class="form-group">
    {!! Form::label('twitter_username', 'Twitter_username:') !!}
    {!! Form::text('twitter_username', null, ['class' => 'form-control']) !!}
    @if($errors->has('twitter_username'))
        <div class="alert-danger">
            {!! $errors->first('twitter_username') !!}
        </div>
    @endif
</div>

<!-- Github_username Field -->
<div class="form-group">
    {!! Form::label('github_username', 'Github_username:') !!}
    {!! Form::text('github_username', null, ['class' => 'form-control']) !!}
    @if($errors->has('github_username'))
        <div class="alert-danger">
            {!! $errors->first('github_username') !!}
        </div>
    @endif
</div>

<!-- Update Profile Field -->
<div class="form-group">
    {!! Form::submit('Update Profile', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}