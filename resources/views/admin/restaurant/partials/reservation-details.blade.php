<br/>

<div class="form-group">
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
