@if(!empty($facility->translate()))
    @foreach ($locales as $locale)
        <div class="form-group">
            {!! Form::label('I18N['.$locale->lang.'][name]', 'Facility Name '.$locale->lang.':') !!}
            @if(!empty($facility->translate($locale->lang)))
            {!! Form::text('I18N['.$locale->lang.'][name]',  @$facility->translate($locale->lang)->name  , ['class' => 'form-control']) !!}
            @else
            {!! Form::text('I18N['.$locale->lang.'][name]',  ''  , ['class' => 'form-control']) !!}
            @endif
            @if($errors->has('I18N.'.$locale->lang.'.name'))
                <div class="alert-danger">
                    {!! $errors->first('I18N.'.$locale->lang.'.name') !!}
                </div>
            @endif
        </div>
    @endforeach


    @foreach ($locales as $locale)
        <div class="form-group">
            {!! Form::label('I18N['.$locale->lang.'][description]', 'Description '.$locale->lang.':') !!}
            @if(!empty($facility->translate($locale->lang)))
            {!! Form::textarea('I18N['.$locale->lang.'][description]', @$facility->translate($locale->lang)->description , ['class' => 'form-control']) !!}
            @else
            {!! Form::textarea('I18N['.$locale->lang.'][description]', '' , ['class' => 'form-control']) !!}
            @endif
            @if($errors->has('I18N.'.$locale->lang.'description'))
                <div class="alert-danger">
                    {!! $errors->first('I18N.'.$locale->lang.'description') !!}
                </div>
            @endif
        </div>
    @endforeach

@else

    @foreach ($locales as $locale)

        <div class="form-group">
            {!! Form::label('I18N['.$locale->lang.'][name]', 'Facility Name '.$locale->lang.':') !!}
            {!! Form::text('I18N['.$locale->lang.'][name]', null , ['class' => 'form-control']) !!}
            @if($errors->has('I18N.'.$locale->lang.'.name'))
                <div class="alert-danger">
                    {!! $errors->first('I18N.'.$locale->lang.'.name') !!}
                </div>
            @endif
        </div>
    @endforeach


    @foreach ($locales as $locale)
        <div class="form-group">
            {!! Form::label('I18N['.$locale->lang.'][description]', 'Description '.$locale->lang.':') !!}
            {!! Form::textarea('I18N['.$locale->lang.'][description]', null , ['class' => 'form-control']) !!}
            @if($errors->has('I18N.'.$locale->lang.'.description'))
                <div class="alert-danger">
                    {!! $errors->first('I18N.'.$locale->lang.'.description') !!}
                </div>
            @endif
        </div>
    @endforeach

@endif

<div class="row">
    <div class="col-md-5">
        <div class="form-group">

            {!! Form::label('icon', 'Icon :') !!}
            <br/>
            <div class="btn-group">

                <button type="button" class="btn btn-success">
                    <i class="facility-icon {{ $facility->icon }}"></i>
                    <span class="select-icon">
                        {{ $facility->icon ? $facility->icon : 'Select Facility Icon' }}
                    </span>
                </button>
                <button type="button" class="icp icp-dd btn btn-success " data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <div class="dropdown-menu"></div>
            </div>

            {!! Form::hidden('icon', null, ['class' => 'form-control facility-icon-hidden']) !!}
            @if($errors->has('icon'))
                <div class="alert-danger">
                    {!! $errors->first('icon') !!}
                </div>
            @endif
        </div>
    </div>
</div>

<hr/>