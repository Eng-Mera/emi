<input type="hidden" name="_token" value="{{ csrf_token() }}">

<div class="form-group has-feedback">
    <input type="text" class="form-control" placeholder="Role Name" name="display_name"
           value="{{ $role->display_name }}"/>
</div>


<div class="form-group has-feedback">
    <input type="text" class="form-control" placeholder="Role Color" name="color" value="{{ $role->color }}"/>
</div>

<div class="form-group has-feedback">
    <label>Choose Role Permissions</label>
    {!! Form::select('permissions[]', array_pluck(\App\Permission::all()->toArray(), 'display_name', 'id'), array_pluck($role->perms()->get()->toArray(), 'id'), ['multiple'=>'multiple', 'class' => 'form-control']) !!}
    @if($errors->has('role'))
        <div class="alert-danger">
            {!! $errors->first('role') !!}
        </div>
    @endif
</div>

<div class="form-group has-feedback">
    <textarea class="form-control" placeholder="Role description" name="description">{{ $role->description }}</textarea>
</div>

<div class="form-group has-feedback">

    <label>Choose Role routes</label>

    {!! Form::select('routes[]', $routes, $selected, ['multiple'=>'multiple', 'class' => 'form-control']) !!}

    @if($errors->has('routes'))
        <div class="alert-danger">
            {!! $errors->first('routes') !!}
        </div>
    @endif

</div>

<div class="row">
    <div class="col-xs-2">
        <button type="submit" class="btn btn-primary btn-block btn-flat">{{ $action }} Role</button>
    </div><!-- /.col -->
</div>