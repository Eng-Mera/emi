@if(\App\User::getCurrentUser()->hasRole(['dev-admin']))
    <div class="form-group">
        {!! Form::label('role', 'User Role:') !!}
        {!! Form::select('roles[]', array_pluck(\App\Role::all()->toArray(), 'display_name', 'id'), array_pluck($user->roles->toArray(), 'id'), ['multiple'=>'multiple', 'class' => 'form-control']) !!}
        @if($errors->has('role'))
            <div class="alert-danger">
                {!! $errors->first('role') !!}
            </div>
        @endif
    </div>
@elseif(\App\User::getCurrentUser()->hasRole(['super-admin']))

    <div class="form-group">
        {!! Form::label('role', 'User Role:') !!}
        {!! Form::select('roles[]', array_pluck(\App\Role::where('name', '<>', \App\Role::DEV_ADMIN)->get()->toArray(), 'display_name', 'id'), array_pluck($user->roles->toArray(), 'id'), ['multiple'=>'multiple', 'class' => 'form-control']) !!}
        @if($errors->has('role'))
            <div class="alert-danger">
                {!! $errors->first('role') !!}
            </div>
        @endif
    </div>
@elseif(\App\User::getCurrentUser()->hasRole(['restaurant-manager']))

    <div class="form-group">
        {!! Form::label('roles', 'User Role:') !!}
        {!! Form::select('roles[]', array_pluck(\App\Role::whereIn('name', [\App\Role::RESERVATION_MANAGER, \App\Role::RESTAURANT_ADMIN])->get()->toArray(), 'display_name', 'id'), array_pluck($user->roles->toArray(), 'id'), ['multiple'=>'multiple', 'class' => 'form-control']) !!}
        @if($errors->has('roles'))
            <div class="alert-danger">
                {!! $errors->first('roles') !!}
            </div>
        @endif
    </div>
@endif
