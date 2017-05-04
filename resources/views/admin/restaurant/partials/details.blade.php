<br/>
<div class="form-group">
    {!! Form::label('in_out_door', 'In/Out door:') !!}
    {!! Form::select('in_out_door', $in_out_door, null, ['class' => 'form-control']) !!}
    @if($errors->has('in_out_door'))
        <div class="alert-danger">
            {!! $errors->first('in_out_door') !!}
        </div>
    @endif
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-4">
            {!! Form::label('facilities', 'Facilities:') !!}
            {!! Form::select('facilities[]', $facilities, $restaurant_facilities, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
            @if($errors->has('facilities'))
                <div class="alert-danger">
                    {!! $errors->first('facilities') !!}
                </div>
            @endif
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-4">
            {!! Form::label('categories', 'Categories:') !!}
            {!! Form::select('categories[]', $categories, $restaurant_categories, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
            @if($errors->has('categories'))
                <div class="alert-danger">
                    {!! $errors->first('categories') !!}
                </div>
            @endif
        </div>
    </div>
</div>

<div class="form-group">
    <h4>Price Range</h4>
    <div class="row">
        <div class="col-md-2">
            {!! Form::label('price', 'From:') !!}
            {!! Form::number('price_from', null, ['class' => 'form-control']) !!}
            @if($errors->has('price_from'))
                <div class="alert-danger">
                    {!! $errors->first('price_from') !!}
                </div>
            @endif
        </div>
        <div class="col-md-2">
            {!! Form::label('price', 'To:') !!}
            {!! Form::number('price_to', null, ['class' => 'form-control']) !!}
            @if($errors->has('price_to'))
                <div class="alert-danger">
                    {!! $errors->first('price_to') !!}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Password Field -->
<div class="form-group">
    {!! Form::label('dress_code', 'Dress code:') !!}
    {!! Form::text('dress_code', null, ['class' => 'form-control']) !!}
    @if($errors->has('dress_code'))
        <div class="alert-danger">
            {!! $errors->first('dress_code') !!}
        </div>
    @endif
</div>