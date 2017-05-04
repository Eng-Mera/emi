<h1>Menu Item details</h1>

{!!  Form::model($menuitem, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'route' => ['admin.restaurant.menu-item.store',  $restaurant_slug ]]) !!}

{{--@include('admin.menuitem.partials.restaurant-admins')--}}

@include('admin.menuitem.partials.form-fields')

        <!-- Update Profile Field -->
<div class="form-group">
    {!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}