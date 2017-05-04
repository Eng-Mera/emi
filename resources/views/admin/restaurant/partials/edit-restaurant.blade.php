<br/>
{!!  Form::model($restaurant, ['method' => 'PATCH', 'enctype' => 'multipart/form-data', 'route' => ['admin.restaurant.update', $restaurant->slug]]) !!}

<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#ownership" aria-controls="home" role="tab" data-toggle="tab">Restaurant
            Ownership</a></li>
    <li role="presentation" class=""><a href="#details" aria-controls="home" role="tab" data-toggle="tab">Restaurant
            Details </a></li>
    <li role="presentation" class=""><a href="#more" aria-controls="home" role="tab" data-toggle="tab">More Details </a>
    </li>
    <li role="presentation" class=""><a href="#social" aria-controls="home" role="tab" data-toggle="tab">Social
            Media</a></li>
    <li role="presentation" class=""><a href="#contact" aria-controls="home" role="tab" data-toggle="tab">Contact
            Details</a></li>
    @if($restaurant->reservable_online)
        <li role="presentation" class=""><a href="#reservation-details" aria-controls="home" role="tab"
                                            data-toggle="tab">Online Reservation</a></li>
    @endif
    @role(['super-admin'])
    <li role="presentation" class=""><a href="#super-admin-controls" aria-controls="home" role="tab" data-toggle="tab">Super
            Admin Controls</a></li>
    @endrole

</ul>

<div class="tab-content">
    <div class="tab-pane active" id="ownership">
        @include('admin.restaurant.partials.restaurant-admins')
    </div>
    <div class="tab-pane" id="details">
        @include('admin.restaurant.partials.form-fields')
    </div>
    <div class="tab-pane" id="more">
        @include('admin.restaurant.partials.details')
    </div>
    <div class="tab-pane" id="social">
        @include('admin.restaurant.partials.social-media')
    </div>
    <div class="tab-pane" id="contact">
        @include('admin.restaurant.partials.contact')
    </div>
    @if($restaurant->reservable_online)
        <div class="tab-pane" id="reservation-details">
            @include('admin.restaurant.partials.reservation-details')
        </div>
    @endif
    @role(['super-admin'])
    <div class="tab-pane" id="super-admin-controls">
        @include('admin.restaurant.partials.super-admin-controls')
    </div>
    @endrole
</div>

<!-- Update Profile Field -->
<div class="form-group">
    {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}