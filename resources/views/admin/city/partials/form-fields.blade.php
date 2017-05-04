@if(!empty($city->translate()))
    @foreach($locales as $locale)
        <div class="form-group">
            {!! Form::label('I18N['.$locale->lang.'][city_name]', 'City name '.$locale->lang.':') !!}
            @if(!empty($city->translate($locale->lang)))
            {!! Form::text('I18N['.$locale->lang.'][city_name]', $city->translate($locale->lang)->city_name , ['class' => 'form-control']) !!}
            @else
            {!! Form::text('I18N['.$locale->lang.'][city_name]', '' , ['class' => 'form-control']) !!}
            @endif
            @if($errors->has('I18N['.$locale->lang.'][city_name]'))
                <div class="alert-danger">
                    {!! $errors->first('I18N['.$locale->lang.'][city_name]') !!}
                </div>
            @endif
        </div>
    @endforeach
@else
    @foreach($locales as $locale)
        <div class="form-group">
            {!! Form::label('I18N['.$locale->lang.'][city_name]', 'City name '.$locale->lang.':') !!}
            {!! Form::text('I18N['.$locale->lang.'][city_name]', null , ['class' => 'form-control']) !!}
            @if($errors->has('I18N['.$locale->lang.'][city_name]'))
                <div class="alert-danger">
                    {!! $errors->first('I18N['.$locale->lang.'][city_name]') !!}
                </div>
            @endif
        </div>
    @endforeach
@endif