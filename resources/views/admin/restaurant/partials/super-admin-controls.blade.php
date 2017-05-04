@role(['super-admin'])
<br/>
<div class="form-group">
    {!! Form::label('allow_job_vacancies', 'Allow Job Vacancy:') !!}
    {!! Form::checkbox('allow_job_vacancies', 1, $restaurant->allow_job_vacancies) !!}
    @if($errors->has('allow_job_vacancies'))
        <div class="alert-danger">
            {!! $errors->first('allow_job_vacancies') !!}
        </div>
    @endif
</div>

<div class="form-group">
    {!! Form::label('reservable_online', 'Allow Online Reservation:') !!}
    {!! Form::checkbox('reservable_online', 1, $restaurant->reservable_online) !!}
    @if($errors->has('reservable_online'))
        <div class="alert-danger">
            {!! $errors->first('reservable_online') !!}
        </div>
    @endif
</div>

@if(!$restaurant->reservable_online)
    <div id="temp-price-field" class="form-group{{ $errors->has('amount') ?: ' hide' }}">
        <div class="row">
            <div class="col-md-3">
                {!! Form::label('amount', 'Price / person:') !!}
                {!! Form::number('amount', null,['class' => 'form-control']) !!} EGP
                @if($errors->has('amount'))
                    <div class="alert-danger">
                        {!! $errors->first('amount') !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif

<div class="form-group">
    {!! Form::label('is_trendy', 'Allow Trendy:') !!}
    {!! Form::checkbox('is_trendy', 1, $restaurant->is_trendy) !!}
    @if($errors->has('is_trendy'))
        <div class="alert-danger">
            {!! $errors->first('is_trendy') !!}
        </div>
    @endif
</div>
@endrole
