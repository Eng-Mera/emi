<h1>Facility details</h1>

{!!  Form::model($facility, ['method' => 'PATCH', 'enctype' => 'multipart/form-data', 'route' => ['admin.facility.update', $facility->id]]) !!}

@include('admin.facility.partials.form-fields')

<!-- Update Profile Field -->
<div class="form-group">
    {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}