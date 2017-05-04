@if(\App\User::getCurrentUser()->hasRole([\App\Role::SUPER_ADMIN]))
    <br/>
    <div class="form-group">
        {!! Form::label('owner_id', 'Owner:') !!}
        {!! Form::select('owner_id', $restaurant_manager, $restaurant->owner_id, ['class' => 'form-control']) !!}
        @if($errors->has('owner_id'))
            <div class="alert-danger">
                {!! $errors->first('owner_id') !!}
            </div>
        @endif
    </div>
@endif

@if(\App\User::getCurrentUser()->hasRole([\App\Role::SUPER_ADMIN]))
    <div class="form-group">
        {!! Form::label('managers', 'Managers:') !!}
        {!! Form::select('managers[]', $restaurant_admins, $restaurant->managers->pluck('id')->toArray(), ['class' => 'form-control', 'multiple' => 'multiple']) !!}
        @if($errors->has('managers'))
            <div class="alert-danger">
                {!! $errors->first('managers') !!}
            </div>
        @endif
    </div>
@elseif(\App\User::getCurrentUser()->hasRole([\App\Role::RESTAURANT_MANAGER]) && $restaurant->reservable_online)
    <div class="form-group">
        {!! Form::label('managers', 'Managers:') !!}
        {!! Form::select('managers[]', $restaurant_admins, $restaurant->managers->pluck('id')->toArray(), ['class' => 'form-control', 'multiple' => 'multiple']) !!}
        @if($errors->has('managers'))
            <div class="alert-danger">
                {!! $errors->first('managers') !!}
            </div>
        @endif
    </div>
@endif
