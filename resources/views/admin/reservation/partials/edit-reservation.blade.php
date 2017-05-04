<h1>Reservation details</h1>

{!!  Form::model($reservation, ['method' => 'PATCH', 'enctype' => 'multipart/form-data', 'route' => ['reservation-change', $reservation->id]]) !!}

@include('admin.reservation.partials.form-fields')

<!-- Update Profile Field -->
<div class="form-group">
    {!! Form::submit('Change', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}