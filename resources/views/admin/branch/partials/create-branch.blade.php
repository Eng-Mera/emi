<h1>Branch details</h1>

{!!  Form::model($branch, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'route' => ['admin.restaurant.branch.store',  $restaurant_slug ]]) !!}

{{--@include('admin.menuitem.partials.restaurant-admins')--}}

@include('admin.branch.partials.form-fields')

<!-- Update Profile Field -->
<div class="form-group">
    {!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}