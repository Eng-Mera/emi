<h1>Claim Approve</h1>

{!!  Form::model($claim, ['method' => 'PATCH', 'enctype' => 'multipart/form-data', 'route' => ['admin.claim.update', $claim->id]]) !!}

@include('admin.claim.partials.claim-form')

<!-- Update Profile Field -->
<div class="form-group">
    {!! Form::submit('Approve', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}