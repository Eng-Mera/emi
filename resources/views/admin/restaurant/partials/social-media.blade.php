<br/>
<div class="form-group">
    {!! Form::label('facebook', 'Facebook Page:') !!}
    {!! Form::text('facebook', null,['class' => 'form-control']) !!}
    @if($errors->has('facebook'))
        <div class="alert-danger">
            {!! $errors->first('facebook') !!}
        </div>
    @endif
</div>

<div class="form-group">
    {!! Form::label('twitter', 'Twitter account:') !!}
    {!! Form::text('twitter', null,['class' => 'form-control']) !!}
    @if($errors->has('twitter'))
        <div class="alert-danger">
            {!! $errors->first('twitter') !!}
        </div>
    @endif
</div>

<div class="form-group">
    {!! Form::label('instagram', 'Instagram:') !!}
    {!! Form::text('instagram', null,['class' => 'form-control']) !!}
    @if($errors->has('instagram'))
        <div class="alert-danger">
            {!! $errors->first('instagram') !!}
        </div>
    @endif
</div>