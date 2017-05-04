<br/>

@if(!empty($restaurant->translate()))
    @foreach($locales as $locale)
        <div class="form-group">
            {!! Form::label('I18N['.$locale->lang.'][name]', 'Restaurant name '.$locale->lang.':') !!}
            @if(!empty($restaurant->translate($locale->lang)))
            {!! Form::text('I18N['.$locale->lang.'][name]', @$restaurant->translate($locale->lang)->name , ['class' => 'form-control']) !!}
            @else
            {!! Form::text('I18N['.$locale->lang.'][name]', '' , ['class' => 'form-control']) !!}
            @endif
            @if($errors->has('I18N.'.$locale->lang.'.name'))
                <div class="alert-danger">
{{--                    {!! $errors->first('I18N['.$locale->lang.'][name]') !!}--}}
                    {!! $errors->first('I18N.'.$locale->lang.'.name') !!}
                </div>
            @endif
        </div>
    @endforeach
@else
    @foreach($locales as $locale)
        <div class="form-group">
            {!! Form::label('I18N['.$locale->lang.'][name]', 'Restaurant name '.$locale->lang.':') !!}
            {!! Form::text('I18N['.$locale->lang.'][name]', null , ['class' => 'form-control']) !!}
            @if($errors->has('I18N.'.$locale->lang.'.name'))
                <div class="alert-danger">
                    {{--{!! $errors->first('I18N['.$locale->lang.'][name]') !!}--}}
                    {!! $errors->first('I18N.'.$locale->lang.'.name') !!}
                </div>
            @endif
        </div>
    @endforeach
@endif

<div class="form-group">
    {!! Form::label('slug', 'Slug:') !!}
    {!! Form::text('slug', null, ['class' => 'form-control']) !!}
    @if($errors->has('slug'))
        <div class="alert-danger">
            {!! $errors->first('slug') !!}
        </div>
    @endif
</div>

<div class="form-group">
    {!! Form::label('type', 'Restaurant Type:') !!}
    {!! Form::select('type', $types, null, ['class' => 'form-control']) !!}
    @if($errors->has('type'))
        <div class="alert-danger">
            {!! $errors->first('type') !!}
        </div>
    @endif
</div>

<div class="form-group">
    {!! Form::label('htr_stars', 'HTR Stars:') !!}
    {!! Form::select('htr_stars', $htrStars, null, ['class' => 'form-control']) !!}
    @if($errors->has('htr_stars'))
        <div class="alert-danger">
            {!! $errors->first('htr_stars') !!}
        </div>
    @endif
</div>


<div class="form-group">
    {!! Form::label('city_id', 'City:') !!}
    {!! Form::select('city_id', $cities, $restaurant->city_id, ['class' => 'form-control']) !!}
    @if($errors->has('city_id'))
        <div class="alert-danger">
            {!! $errors->first('city_id') !!}
        </div>
    @endif
</div>

@if(!empty($restaurant->translate()))
    @foreach($locales as $locale)
        <div class="form-group">
            {!! Form::label('I18N['.$locale->lang.'][description]', 'Description '.$locale->lang.':') !!}
            @if(!empty($restaurant->translate($locale->lang)))
            {!! Form::textarea('I18N['.$locale->lang.'][description]', @$restaurant->translate($locale->lang)->description , ['class' => 'form-control']) !!}
            @else
            {!! Form::textarea('I18N['.$locale->lang.'][description]', '' , ['class' => 'form-control']) !!}
            @endif
            @if($errors->has('I18N.'.$locale->lang.'.description'))
                <div class="alert-danger">
{{--                    {!! $errors->first('I18N['.$locale->lang.'][description]') !!}--}}
                    {!! $errors->first('I18N.'.$locale->lang.'.description') !!}
                </div>
            @endif
        </div>
    @endforeach
@else
    @foreach($locales as $locale)
        <div class="form-group">
            {!! Form::label('I18N['.$locale->lang.'][description]', 'Description '.$locale->lang.':') !!}
            {!! Form::textarea('I18N['.$locale->lang.'][description]', null , ['class' => 'form-control']) !!}
            @if($errors->has('I18N.'.$locale->lang.'.description'))
                <div class="alert-danger">
                    {!! $errors->first('I18N.'.$locale->lang.'.description') !!}
                </div>
            @endif
        </div>
    @endforeach
@endif

<!-- Email Field -->


<hr/>
<div class="container">

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('logo', 'Logo:') !!}
            <br/>
            @if ($restaurant->logo)
                <img data-upload-img="1" src="{{url('file/resize', [100, $restaurant->logo->filename])}}" alt=""
                     class="img-circle" width="100"/>
            @else
                <img data-upload-img="1" src="" alt="" class="img-circle" width="100"/>
            @endif
            <br/>
            <br/>

            {!! Form::hidden('logo', null, ['class' => 'form-control', 'data-uploaded-field' => 1, 'disabled' => 'disabled']) !!}
            <input type="file" id="logo"/>

            @if($errors->has('logo'))
                <div class="alert-danger">
                    {!! $errors->first('logo') !!}
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('featured', 'Featured Image:') !!}
            <br/>
            @if ($restaurant->featured)
                <img data-upload-img="1" src="{{url('file/resize', [100, $restaurant->featured->filename])}}" alt=""
                     class="img-circle" width="100"/>
            @else
                <img data-upload-img="1" src="" alt="" class="img-circle" width="100"/>
            @endif
            <br/>
            <br/>

            {!! Form::hidden('featured_image', null, ['class' => 'form-control', 'data-uploaded-field' => 1, 'disabled' => 'disabled']) !!}
            <input type="file" id="featured_image"/>

            @if($errors->has('featured_image'))
                <div class="alert-danger">
                    {!! $errors->first('featured_image') !!}
                </div>
            @endif

        </div>

    </div>
</div>