<div class="form-group">
    {!! Form::label('name', 'Movie name') !!}
    {!! Form::text('name', $movie->name , ['class' => 'form-control']) !!}
    @if($errors->has('name'))
        <div class="alert-danger">
            {!! $errors->first('name') !!}
        </div>
    @endif
</div>

<div class="form-group">
    {!! Form::label('description', 'Movie Description') !!}
    {!! Form::textarea('description', $movie->description , ['class' => 'form-control']) !!}
    @if($errors->has('description'))
        <div class="alert-danger">
            {!! $errors->first('description') !!}
        </div>
    @endif
</div>

<div class="form-group">
    {!! Form::label('enable_booking', 'Enable Booking') !!}
    {!! Form::checkbox('enable_booking', 1, $movie->enable_booking      ) !!}
    @if($errors->has('enable_booking'))
        <div class="alert-danger">
            {!! $errors->first('enable_booking') !!}
        </div>
    @endif
</div>

<div class="form-group">
    {!! Form::label('booking_url', 'Booking Url') !!}
    {!! Form::text('booking_url', $movie->booking_url , ['class' => 'form-control']) !!}
    @if($errors->has('booking_url'))
        <div class="alert-danger">
            {!! $errors->first('booking_url') !!}
        </div>
    @endif
</div>

<br/>

<div class="row">
    <div class="col-md-4">

        <div class="form-group">

            {!! Form::label('poster', 'Poster Image:') !!}
            <br/>
            @if ($movie->poster)
                <img data-upload-img="1" src="{{url('file/resize', [100, $movie->poster->filename])}}" alt=""
                     class="img-circle" width="100"/>
            @else
                <img data-upload-img="1" src="" alt="" class="img-circle" width="100"/>
            @endif
            <br/>
            <br/>


            {!! Form::hidden('poster', null, ['class' => 'form-control', 'data-uploaded-field' => 1, 'disabled' => 'disabled']) !!}
            <input type="file" id="poster"/>

            @if($errors->has('poster'))
                <div class="alert-danger">
                    {!! $errors->first('poster') !!}
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-4">

        <div class="form-group">

            {!! Form::label('movie_featured_image', 'Featured Image:') !!}
            <br/>
            @if ($movie->featuredImage)
                <img data-upload-img="1" src="{{url('file/resize', [100, $movie->featuredImage->filename])}}" alt=""
                     class="img-circle" width="100"/>
            @else
                <img data-upload-img="1" src="" alt="" class="img-circle" width="100"/>
            @endif
            <br/>
            <br/>

            {!! Form::hidden('movie_featured_image', null, ['class' => 'form-control', 'data-uploaded-field' => 1, 'disabled' => 'disabled']) !!}
            <input type="file" id="poster"/>

            @if($errors->has('movie_featured_image'))
                <div class="alert-danger">
                    {!! $errors->first('movie_featured_image') !!}
                </div>
            @endif
        </div>

        <div class="form-group">
            {!! Form::label('add_to_featured', 'Add to Featured Movies') !!}
            {!! Form::checkbox('add_to_featured', 1, $movie->add_to_featured) !!}
            @if($errors->has('add_to_featured'))
                <div class="alert-danger">
                    {!! $errors->first('add_to_featured') !!}
                </div>
            @endif
        </div>
    </div>

</div>
