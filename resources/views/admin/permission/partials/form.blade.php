<input type="hidden" name="_token" value="{{ csrf_token() }}">

<div class="form-group has-feedback">
    <input type="text" class="form-control" placeholder="Permission Name" name="display_name"
           value="{{ $permission->display_name }}"/>
</div>

<div class="form-group has-feedback">
                <textarea class="form-control" placeholder="Permission description"
                          name="description">{{ $permission->description }}</textarea>
</div>

<div class="form-group has-feedback">

    <label>Choose Route For this Permissions</label>

    {!! Form::select('routes[]', $routes, $selected, ['multiple'=>'multiple', 'class' => 'form-control']) !!}

    @if($errors->has('routes'))
        <div class="alert-danger">
            {!! $errors->first('routes') !!}
        </div>
    @endif

</div>

<div class="row">
    <div class="col-xs-2">
        <button type="submit" class="btn btn-primary btn-block btn-flat">{{ $action }} Permission</button>
    </div><!-- /.col -->
</div>