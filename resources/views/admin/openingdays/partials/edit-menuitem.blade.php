<h1>Menu Item details</h1>

{!!  Form::model($menuitem, ['method' => 'PATCH', 'enctype' => 'multipart/form-data', 'route' => ['admin.restaurant.opening-days.update', $restaurant_slug, $menuitem->slug]]) !!}

@include('admin.menuitem.partials.form-fields')

        <!-- Update Profile Field -->
<div class="form-group">
    {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}