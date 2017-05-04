<br/>
<!-- Email Field -->
<div class="form-group">
    {!! Form::label('phone', 'Phone:') !!}
    {!! Form::text('phone', null, ['class' => 'form-control']) !!}
    @if($errors->has('phone'))
        <div class="alert-danger">
            {!! $errors->first('phone') !!}
        </div>
    @endif
</div>

<!-- Email Field -->
<div class="form-group">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
    @if($errors->has('email'))
        <div class="alert-danger">
            {!! $errors->first('email') !!}
        </div>
    @endif
</div>

@if(!empty($restaurant->translate()))
    @foreach($locales as $locale)
        <div class="form-group">
            {!! Form::label('I18N['.$locale->lang.'][address]', 'Address '.$locale->lang.':') !!}
            @if(!empty($restaurant->translate($locale->lang)))
            {!! Form::text('I18N['.$locale->lang.'][address]', @$restaurant->translate($locale->lang)->address , ['class' => 'form-control']) !!}
            @else
            {!! Form::text('I18N['.$locale->lang.'][address]', '' , ['class' => 'form-control']) !!}
            @endif
            @if($errors->has('I18N.'.$locale->lang.'.address'))
                <div class="alert-danger">
                    {!! $errors->first('I18N.'.$locale->lang.'.address') !!}
                </div>
            @endif
        </div>
    @endforeach
@else
    @foreach($locales as $locale)
        <div class="form-group">
            {!! Form::label('I18N['.$locale->lang.'][address]', 'Address '.$locale->lang.':') !!}
            {!! Form::text('I18N['.$locale->lang.'][address]', null , ['class' => 'form-control']) !!}
            @if($errors->has('I18N.'.$locale->lang.'.address'))
                <div class="alert-danger">
                    {!! $errors->first('I18N.'.$locale->lang.'.address') !!}
                </div>
            @endif
        </div>
    @endforeach
@endif



<!-- Date of birth Field -->
<div class="form-group">
    {!! Form::label('latitude', 'Latitude:') !!}
    {!! Form::text('latitude', null, ['class' => 'form-control']) !!}
    @if($errors->has('latitude'))
        <div class="alert-danger">
            {!! $errors->first('latitude') !!}
        </div>
    @endif
</div>

<!-- Date of birth Field -->
<div class="form-group">
    {!! Form::label('longitude', 'Longitude:') !!}
    {!! Form::text('longitude', null, ['class' => 'form-control']) !!}
    @if($errors->has('longitude'))
        <div class="alert-danger">
            {!! $errors->first('longitude') !!}
        </div>
    @endif
</div>