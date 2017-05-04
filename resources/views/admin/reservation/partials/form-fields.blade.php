<div class="row">
    <div class="col-md-4">
        <!-- Email Field -->
        <div class="form-group">
            {!! Form::label('date', 'Date:') !!}
            {!! Form::text('date', null, ['class' => 'form-control', 'data-provide' => "datepicker", 'data-date-format' => "yyyy-mm-dd"]) !!}
            @if($errors->has('date'))
                <div class="alert-danger">
                    {!! $errors->first('date') !!}
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <!-- Email Field -->
        <div class="form-group">
            {!! Form::label('time', 'Time:') !!}
            {!! Form::text('time', null, ['class' => 'form-control bootstrap-timepicker' ]) !!}
            @if($errors->has('time'))
                <div class="alert-danger">
                    {!! $errors->first('time') !!}
                </div>
            @endif
        </div>
    </div>


</div>

<!-- Email Field -->
<div class="form-group">
    {!! Form::label('number_of_people', 'Number of Seats:') !!}
    {!! Form::number('number_of_people', null, ['class' => 'form-control','min'=>1]) !!}
    @if($errors->has('number_of_people'))
        <div class="alert-danger">
            {!! $errors->first('number_of_people') !!}
        </div>
    @endif
</div>

<div class="form-group">
    {!! Form::label('coupon_code', 'Coupon Code:') !!}
    {!! Form::text('coupon_code', null, ['class' => 'form-control']) !!}
    @if($errors->has('coupon_code'))
        <div class="alert-danger">
            {!! $errors->first('coupon_code') !!}
        </div>
    @endif
</div>

<div class="form-group">
    {!! Form::label('advance_payment', 'Advance Payment :') !!}
    {!! Form::checkbox('advance_payment', 1, $reservation->advance_payment) !!}
    @if($errors->has('advance_payment'))
        <div class="alert-danger">
            {!! $errors->first('advance_payment') !!}
        </div>
    @endif
</div>

<!-- Email Field -->
<div class="form-group">
    {!! Form::label('option', 'In/Out door:') !!}
    {!! Form::select('option', ['' => 'Choose In/Out door ','INOUT' => 'Both','INDOORS' => 'INDOOR','OUTDOORS' => 'OUTDOOR'],null, ['class' => 'form-control']) !!}
    @if($errors->has('option'))
        <div class="alert-danger">
            {!! $errors->first('option') !!}
        </div>
    @endif
</div>
