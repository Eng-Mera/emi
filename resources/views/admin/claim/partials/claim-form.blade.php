<div class="form-group">
    {!! Form::label('user_id', 'User:') !!}
    {!! Form::select('user_id', $claims, null, ['class' => 'form-control']) !!}
    @if($errors->has('user_id'))
        <div class="alert-danger">
            {!! $errors->first('user_id') !!}
        </div>
    @endif
</div>

<div class="form-group">
    {!! Form::label('slug', 'Restaurant:') !!}
    {!! Form::select('slug', $restaurants, null, ['class' => 'form-control']) !!}
    @if($errors->has('slug'))
        <div class="alert-danger">
            {!! $errors->first('slug') !!}
        </div>
    @endif
</div>


