<h1>Menu Item details</h1>

{!!  Form::model($menuItem, ['method' => 'PATCH', 'enctype' => 'multipart/form-data', 'route' => ['admin.restaurant.menu-item.update', $restaurant_slug, $menuItem->slug]]) !!}

@include('admin.menuitem.partials.form-fields')

        <!-- Update Profile Field -->
<div class="form-group">
    {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}