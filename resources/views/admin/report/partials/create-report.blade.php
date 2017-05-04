<h1>Report Information</h1>

{!!  Form::model($report, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'route' => ['admin.report.store']]) !!}

@include('admin.report.partials.report-users')

@include('admin.report.partials.form-fields')

        <!-- Update Profile Field -->
<div class="form-group">
    {!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}