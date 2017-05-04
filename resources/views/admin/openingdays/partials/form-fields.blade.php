<div class="form-group">
    {!! Form::label('day_name', 'Item name:') !!}
    {!! Form::select('day_name', $week_days, null, ['class' => 'form-control']) !!}
    @if($errors->has('day_name'))
        <div class="alert-danger">
            {!! $errors->first('day_name') !!}
        </div>
    @endif
</div>

<!-- Email Field -->
<div class="form-group">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::checkbox('status', 1,$openingDay->status) !!}
    @if($errors->has('status'))
        <div class="alert-danger">
            {!! $errors->first('status') !!}
        </div>
    @endif
</div>

<div class="form-group">
    <h4>Opening Time</h4>
    <div class="row">
        <div class="col-md-2">
            {!! Form::label('from', 'From:') !!}
            {!! Form::time('from', null, ['class' => 'form-control']) !!}
            @if($errors->has('from'))
                <div class="alert-danger">
                    {!! $errors->first('from') !!}
                </div>
            @endif
        </div>
        <div class="col-md-2">
            {!! Form::label('to', 'To:') !!}
            {!! Form::time('to', null, ['class' => 'form-control']) !!}
            @if($errors->has('to'))
                <div class="alert-danger">
                    {!! $errors->first('to') !!}
                </div>
            @endif
        </div>
    </div>
</div>