@if(!empty($adminReview->translate()))
    @foreach ($locales as $locale)
        <div class="form-group">
            {!! Form::label('I18N['.$locale->lang.'][restaurant_name]', 'Admin Review '.$locale->lang.':') !!}

            @if(!empty($adminReview->translate($locale->lang)))
                {!! Form::text('I18N['.$locale->lang.'][restaurant_name]', $adminReview->translate($locale->lang)->restaurant_name, ['class' => 'form-control']) !!}
            @else
                {!! Form::text('I18N['.$locale->lang.'][restaurant_name]', '', ['class' => 'form-control']) !!}
            @endif
            @if($errors->has('I18N.'.$locale->lang.'.restaurant_name'))
                <div class="alert-danger">
                    {!! $errors->first('I18N.'.$locale->lang.'.restaurant_name') !!}
                </div>
            @endif
        </div>
    @endforeach
@else
    @foreach ($locales as $locale)
        <div class="form-group">
            {!! Form::label('I18N['.$locale->lang.'][restaurant_name]', 'Admin Review '.$locale->lang.':') !!}
            {!! Form::text('I18N['.$locale->lang.'][restaurant_name]', null, ['class' => 'form-control']) !!}
            @if($errors->has('I18N.'.$locale->lang.'.restaurant_name'))
                <div class="alert-danger">
                    {!! $errors->first('I18N.'.$locale->lang.'.restaurant_name') !!}
                </div>
            @endif
        </div>
    @endforeach
@endif

@if(!empty($adminReview->translate()))
    @foreach ($locales as $locale)
        <div class="form-group">
            {!! Form::label('I18N['.$locale->lang.'][description]', 'Description '.$locale->lang.':') !!}
            @if(!empty($adminReview->translate($locale->lang)))
            {!! Form::textarea('I18N['.$locale->lang.'][description]', $adminReview->translate($locale->lang)->description, ['class' => 'form-control']) !!}
            @else
                {!! Form::textarea('I18N['.$locale->lang.'][description]', '', ['class' => 'form-control']) !!}

            @endif
            @if($errors->has('I18N.'.$locale->lang.'.description'))
                <div class="alert-danger">
                    {!! $errors->first('I18N.'.$locale->lang.'.description') !!}
                </div>
            @endif
        </div>
    @endforeach
@else
    @foreach ($locales as $locale)
        <div class="form-group">
            {!! Form::label('I18N['.$locale->lang.'][description]', 'Description '.$locale->lang.':') !!}
            {!! Form::textarea('I18N['.$locale->lang.'][description]', null, ['class' => 'form-control']) !!}
            @if($errors->has('I18N.'.$locale->lang.'.description'))
                <div class="alert-danger">
                    {!! $errors->first('I18N.'.$locale->lang.'.description') !!}
                </div>
            @endif
        </div>
    @endforeach
@endif

<div class="form-group">

    {!! Form::label('images', 'Images:') !!}
    <br/>
    <div id="images-container">
        @if ($adminReview->images->count())
            <div class="row">
                @foreach($adminReview->images as $image)
                    <div class="img-container col-xs-6 col-md-3">
                        <div class="thumbnail">
                            <a href="#" data-input-name="#deleted_images" data-id="{{ $image->id }}"
                               class="delete-image right"><span class="glyphicon glyphicon-remove"></span></a>
                            <img data-upload-img="1" src="{{url('file/resize', [100, $image->filename])}}" alt=""
                                 class="img-circle" width="100"/>
                        </div>
                    </div>
                @endforeach
            </div>
            {!! Form::hidden('images[]', $image, ['class' => 'form-control', 'data-uploaded-field' => 1, 'disabled' => 'disabled']) !!}
        @else
            {!! Form::hidden('images[]', null, ['class' => 'form-control', 'data-uploaded-field' => 1, 'disabled' => 'disabled']) !!}
        @endif
        {!! Form::hidden('removed_images_ids', null, ['id'=> 'deleted_images']) !!}
    </div>
    <br/>
    <br/>

    <input type="file" multiple id="poster"/>

    @if($errors->has('images'))
        <div class="alert-danger">
            {!! $errors->first('images') !!}
        </div>
    @endif
</div>
