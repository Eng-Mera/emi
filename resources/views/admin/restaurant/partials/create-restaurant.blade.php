<h1>Restaurant Information</h1>

{!!  Form::model($restaurant, ['method' => 'POST', 'enctype' => 'multipart/form-data', 'route' => ['admin.restaurant.store']]) !!}

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
    @role(['super-admin'])
    <li role="presentation" class=""><a href="#super-admin-controls" aria-controls="home" role="tab" data-toggle="tab">Super
            Admin Controls</a></li>
    @endrole
</ul>

<div class="tab-content">

    <div class="tab-pane active" id="ownership">
        @include('admin.restaurant.partials.restaurant-admins')
        <div class="form-group">
            <hr/>

            <a class="pull-right btn btn-primary btnNext" href="#" aria-controls="home" role="tab" data-toggle="tab">Next</a>
        </div>
    </div>
    <div class="tab-pane" id="details">
        @include('admin.restaurant.partials.form-fields')
        <div class="form-group">
            <hr/>

            <a class="btn btn-primary btnPrevious" href="#">Previous</a>
            <a class="pull-right btn btn-primary btnNext" href="#" data-toggle="tab">Next</a>
        </div>
    </div>
    <div class="tab-pane" id="more">
        @include('admin.restaurant.partials.details')
        <div class="form-group">
            <hr/>

            <a class="btn btn-primary btnPrevious" href="#">Previous</a>
            <a class="pull-right btn btn-primary btnNext" href="#" data-toggle="tab">Next</a>
        </div>
    </div>
    <div class="tab-pane" id="social">
        @include('admin.restaurant.partials.social-media')
        <div class="form-group">
            <hr/>

            <a class="btn btn-primary btnPrevious" href="#">Previous</a>
            <a class="pull-right btn btn-primary btnNext" href="#" data-toggle="tab">Next</a>
        </div>
    </div>
    <div class="tab-pane" id="contact">
        @include('admin.restaurant.partials.contact')
        <div class="form-group">
            <hr/>
            <a class="btn btn-primary btnPrevious" href="#">Previous</a>
            @role(['super-admin'])

                <a class="pull-right btn btn-primary btnNext" href="#" data-toggle="tab">Next</a>
            @endrole
        </div>
    </div>
    @role(['super-admin'])
    <div class="tab-pane" id="super-admin-controls">
        @include('admin.restaurant.partials.super-admin-controls')
        <div class="form-group">
            <hr/>

            <a class="btn btn-primary btnPrevious" href="#" data-toggle="tab">Previous</a>
            {!! Form::submit('Create', ['class' => 'pull-right btn btn-success']) !!}
        </div>

    </div>
    @endrole

</div>

<!-- Update Profile Field -->
<br><br>
<div class="form-group">
    {{--{!! Form::submit('Next', ['class' => 'btn btn-primary']) !!}--}}
</div>

{!! Form::close() !!}