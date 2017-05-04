<!-- Email Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
    @if($errors->has('name'))
        <div class="alert-danger">
            {!! $errors->first('name') !!}
        </div>
    @endif
</div>

<div class="row">
    <div class="col-md-4">
        <!-- Email Field -->
        <div class="form-group">
            {!! Form::label('start_date', 'Start date:') !!}
            {!! Form::text('start_date', null, ['class' => 'form-control', 'data-provide' => "datepicker", 'data-date-format' => "yyyy-mm-dd"]) !!}
            @if($errors->has('start_date'))
                <div class="alert-danger">
                    {!! $errors->first('start_date') !!}
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <!-- Email Field -->
        <div class="form-group">
            {!! Form::label('end_date', 'End date:') !!}
            {!! Form::text('end_date', null, ['class' => 'form-control', 'data-provide' => "datepicker", 'data-date-format' => "yyyy-mm-dd"]) !!}
            @if($errors->has('end_date'))
                <div class="alert-danger">
                    {!! $errors->first('end_date') !!}
                </div>
            @endif
        </div>
    </div>


</div>

<!-- Email Field -->
<div class="form-group">
    {!! Form::label('amount', 'Amount:') !!}
    {!! Form::number('amount', null, ['class' => 'form-control']) !!}
    @if($errors->has('amount'))
        <div class="alert-danger">
            {!! $errors->first('amount') !!}
        </div>
    @endif
</div>

<!-- Email Field -->
<div class="form-group">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::select('status', [0 => 'Disabled', 1 => 'Enabled'  ],null, ['class' => 'form-control']) !!}
    @if($errors->has('status'))
        <div class="alert-danger">
            {!! $errors->first('status') !!}
        </div>
    @endif
</div>