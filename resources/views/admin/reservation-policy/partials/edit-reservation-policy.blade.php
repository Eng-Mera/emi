<h1>Reservation Policy details</h1>

{!!  Form::model($reservationPolicy, ['method' => 'PATCH', 'enctype' => 'multipart/form-data', 'route' => ['admin.restaurant.reservation-policy.update', $restaurant_slug, $reservationPolicy->id]]) !!}

@include('admin.reservation-policy.partials.form-fields')

        <!-- Update Profile Field -->
<div class="form-group">
    {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}