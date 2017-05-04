@role('super-admin')
<div class="form-group">
    {!! Form::label('owner_id', 'Owner:') !!}
    {!! Form::select('owner_id', $restaurant_manager, null, ['class' => 'form-control']) !!}
    @if($errors->has('owner_id'))
        <div class="alert-danger">
            {!! $errors->first('owner_id') !!}
        </div>
    @endif
</div>
@endrole