<h1>Personal Information</h1>

{!!  Form::model($user, ['method' => 'PATCH', 'route' => ['admin.user.update', $user->username]]) !!}

User Role

<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
    @if($errors->has('name'))
        <div class="alert-danger">
            {!! $errors->first('name') !!}
        </div>
    @endif
</div>

<!-- Update Profile Field -->
<div class="form-group">
    {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}