<div class="form-group">
    {!! Form::label('job_title_id', 'Job Vacancy name:') !!}
    {!! Form::select('job_title_id', $job_titles,$jobVacancy->job_title_id , ['class' => 'form-control']) !!}
    @if($errors->has('job_title_id'))
        <div class="alert-danger">
            {!! $errors->first('job_title_id') !!}
        </div>
    @endif
</div>


@if(!empty($jobVacancy->translate()))
    @foreach ($locales as $locale)
        <div class="form-group">
            {!! Form::label('I18N['.$locale->lang.'][description]', 'Description '.$locale->lang.':') !!}
            @if(!empty($jobVacancy->translate($locale->lang)))
            {!! Form::textarea('I18N['.$locale->lang.'][description]', $jobVacancy->translate($locale->lang)->description , ['class' => 'form-control']) !!}
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
    @foreach ($locales as $locale)
        <div class="form-group">
            {!! Form::label('I18N['.$locale->lang.'][description]', 'Description '.$locale->lang.':') !!}
            {!! Form::textarea('I18N['.$locale->lang.'][description]', $jobVacancy->description , ['class' => 'form-control']) !!}
            @if($errors->has('I18N['.$locale->lang.'][description]'))
                <div class="alert-danger">
                    {!! $errors->first('I18N['.$locale->lang.'][description]') !!}
                </div>
            @endif
        </div>
    @endforeach
@endif


<div class="form-group">
        {!! Form::label('status', 'Status:') !!}
        {!! Form::select('status', ['0' =>  'Disabled', '1' =>  'Enabled' ],$jobVacancy->status , ['class' => 'form-control']) !!}
        @if($errors->has('status'))
                <div class="alert-danger">
                        {!! $errors->first('status') !!}
                </div>
        @endif
</div>