@if(!empty($jobtitle->translate()))
    @foreach($locales as $locale)
        <div class="form-group">
            {!! Form::label('I18N['.$locale->lang.'][job_title]', 'Job Title name '.$locale->lang.':') !!}
            @if(!empty($jobtitle->translate($locale->lang)))
            {!! Form::text('I18N['.$locale->lang.'][job_title]', $jobtitle->translate($locale->lang)->job_title , ['class' => 'form-control']) !!}
            @else
            {!! Form::text('I18N['.$locale->lang.'][job_title]', '' , ['class' => 'form-control']) !!}
            @endif
            @if($errors->has('I18N['.$locale->lang.'][job_title]'))
                <div class="alert-danger">
                    {!! $errors->first('I18N['.$locale->lang.'][job_title]') !!}
                </div>
            @endif
        </div>
    @endforeach
@else
    @foreach($locales as $locale)
        <div class="form-group">
            {!! Form::label('I18N['.$locale->lang.'][job_title]', 'Job Title name '.$locale->lang.':') !!}
            {!! Form::text('I18N['.$locale->lang.'][job_title]', null, ['class' => 'form-control']) !!}
            @if($errors->has('I18N['.$locale->lang.'][job_title]'))
                <div class="alert-danger">
                    {!! $errors->first('I18N['.$locale->lang.'][job_title]') !!}
                </div>
            @endif
        </div>
    @endforeach
@endif

@if(!empty($jobtitle->translate()))
    @foreach($locales as $locale)
        <div class="form-group">
            {!! Form::label('I18N['.$locale->lang.'][description]', 'Description '.$locale->lang.':') !!}
            @if(!empty($jobtitle->translate($locale->lang)))
            {!! Form::textarea('I18N['.$locale->lang.'][description]', $jobtitle->translate($locale->lang)->description , ['class' => 'form-control']) !!}
            @else
            {!! Form::textarea('I18N['.$locale->lang.'][description]', '' , ['class' => 'form-control']) !!}
            @endif
            @if($errors->has('I18N['.$locale->lang.'][description]'))
                <div class="alert-danger">
                    {!! $errors->first('I18N['.$locale->lang.'][description]') !!}
                </div>
            @endif
        </div>
    @endforeach
@else
    @foreach($locales as $locale)
        <div class="form-group">
                {!! Form::label('I18N['.$locale->lang.'][description]', 'Description '.$locale->lang.':') !!}
                {!! Form::textarea('I18N['.$locale->lang.'][description]', null , ['class' => 'form-control']) !!}
                @if($errors->has('I18N['.$locale->lang.'][description]'))
                        <div class="alert-danger">
                                {!! $errors->first('I18N['.$locale->lang.'][description]') !!}
                        </div>
                @endif
        </div>
    @endforeach
@endif