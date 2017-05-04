<h1>Branch details</h1>

{!!  Form::model($branch, ['method' => 'PATCH', 'enctype' => 'multipart/form-data', 'route' => ['admin.restaurant.branch.update', $restaurant_slug, $branch->slug]]) !!}

@include('admin.branch.partials.form-fields')

<!-- Update Profile Field -->
<div class="form-group">
    {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}