@role('super-admin')
<div class="form-group">
    {!! Form::label('user_id', 'User :') !!}
    {!! Form::select('user_id', $users, null, ['class' => 'form-control']) !!}
    @if($errors->has('user_id'))
        <div class="alert-danger">
            {!! $errors->first('user_id') !!}
        </div>
    @endif
</div>
@endrole