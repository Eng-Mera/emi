<h1>Personal Information</h1>

{!!  Form::model($user, ['method' => 'PATCH', 'route' => ['user.update', $user->username]]) !!}

<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
    @if($errors->has('name'))
        <div class="alert-danger">
            {!! $errors->first('name') !!}
        </div>
    @endif
</div>

<!-- Bio Field -->
<div class="form-group">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
    @if($errors->has('email'))
        <div class="alert-danger">
            {!! $errors->first('email') !!}
        </div>
    @endif
</div>

<!-- Date of birth Field -->
<div class="form-group">
    {!! Form::label('dob', 'Date of birth:') !!}
    {!! Form::text('dob', null, ['class' => 'form-control', 'data-provide' => "datepicker"]) !!}
    @if($errors->has('dob'))
        <div class="alert-danger">
            {!! $errors->first('dob') !!}
        </div>
    @endif
</div>

<hr/>
<h2>Change Password</h2>
<!-- Twitter_username Field -->
<div class="form-group">
    {!! Form::label('current_password', 'Current Password:') !!}
    {!! Form::password('current_password', ['class' => 'form-control']) !!}
    @if($errors->has('current_password'))
        <div class="alert-danger">
            {!! $errors->first('current_password') !!}
        </div>
    @endif
</div>
<div class="form-group">
    {!! Form::label('password', 'Password:') !!}
    {!! Form::password('password', ['class' => 'form-control']) !!}
    @if($errors->has('password'))
        <div class="alert-danger">
            {!! $errors->first('password') !!}
        </div>
    @endif
</div>

<div class="form-group">
    {!! Form::label('password_confirmation', 'Password Confirmation:') !!}
    {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
    @if($errors->has('password_confirmation'))
        <div class="alert-danger">
            {!! $errors->first('password_confirmation') !!}
        </div>
    @endif
</div>

<!-- Update Profile Field -->
<div class="form-group">
    {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}