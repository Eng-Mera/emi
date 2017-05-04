<h1>Facility details</h1>

{!!  Form::model($facility, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'route' => ['admin.facility.store' ]]) !!}


@include('admin.facility.partials.form-fields')

<!-- Update Profile Field -->
<div class="form-group">
    {!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}