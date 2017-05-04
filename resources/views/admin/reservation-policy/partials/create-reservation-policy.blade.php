<h1>Reservation Policy details</h1>

{!!  Form::model($reservationPolicy, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'route' => ['admin.restaurant.reservation-policy.store',  $restaurant_slug ]]) !!}

{{--@include('admin.menuitem.partials.restaurant-admins')--}}

@include('admin.reservation-policy.partials.form-fields')

        <!-- Update Profile Field -->
<div class="form-group">
    {!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}