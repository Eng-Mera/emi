<h1>Picture</h1>
{!!  Form::model($user, ['method' => 'PATCH', 'enctype' => 'multipart/form-data','route' => ['admin.user.update', $user->username]]) !!}
<div class="form-group">
    @if ($user->profilePicture)
        <img src="{{url('file/resize', [100, $user->profilePicture->filename])}}" alt="ALT NAME" class="img-circle"/>
    @endif
    <br/>

    {!! Form::file('uploaded_file', null, ['class' => 'form-control']) !!}

    @if($errors->has('uploaded_file'))
        <div class="alert-danger">
            {!! $errors->first('uploaded_file') !!}
        </div>
    @endif

</div>

<!-- Update Profile Field -->
<div class="form-group">
    {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}