@if(!empty($menuItem->translate()))
    @foreach ($locales as $locale)
        <div class="form-group">
            {!! Form::label('I18N['.$locale->lang.'][name]', 'Item Name '.$locale->lang.':') !!}

            @if(!empty($menuItem->translate($locale->lang)))
                {!!Form::text('I18N['.$locale->lang.'][name]', $menuItem->translate($locale->lang)->name , ['class' => 'form-control']) !!}
            @else
                {!! Form::text('I18N['.$locale->lang.'][name]', '' , ['class' => 'form-control']) !!}
            @endif


        @if($errors->has('I18N['.$locale->lang.'][name]'))
                <div class="alert-danger">
                    {!! $errors->first('I18N['.$locale->lang.'][name]') !!}
                </div>
            @endif
        </div>
    @endforeach
@else
    @foreach ($locales as $locale)
        <div class="form-group">
            {!! Form::label('I18N['.$locale->lang.'][name]', 'Item Name '.$locale->lang.':') !!}
            {!! Form::text('I18N['.$locale->lang.'][name]', null, ['class' => 'form-control']) !!}
            @if($errors->has('I18N['.$locale->lang.'][name]'))
                <div class="alert-danger">
                    {!! $errors->first('I18N['.$locale->lang.'][name]') !!}
                </div>
            @endif
        </div>
    @endforeach
@endif


    <!-- Email Field -->
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
    {!! Form::label('dish_category_id', 'Dish Category:') !!}
    {!! Form::select('dish_category_id', $dish_category, $menuItem->dish_category, ['class' => 'form-control']) !!}
    @if($errors->has('dish_category_id'))
        <div class="alert-danger">
            {!! $errors->first('dish_category_id') !!}
        </div>
    @endif
</div>

@if(!empty($menuItem->translate()))
    @foreach ($locales as $locale)
        <div class="form-group">
            {!! Form::label('I18N['.$locale->lang.'][description]', 'Description '.$locale->lang.':') !!}
            @if(!empty($menuItem->translate($locale->lang)))
                {!! Form::textarea('I18N['.$locale->lang.'][description]', $menuItem->translate($locale->lang)->description, ['class' => 'form-control']) !!}
            @else
                {!! Form::textarea('I18N['.$locale->lang.'][description]', '' , ['class' => 'form-control']) !!}
            @endif

        @if($errors->has('I18N['.$locale->lang.'][description]'))
                <div class="alert-danger">
                    {!! $errors->first('I18N['.$locale->lang.'][description]') !!}
                </div>
            @endif
        </div>
    @endforeach
@else
    @foreach ($locales as $locale)
        <div class="form-group">
            {!! Form::label('I18N['.$locale->lang.'][description]', 'Description '.$locale->lang.':') !!}
            {!! Form::textarea('I18N['.$locale->lang.'][description]', null, ['class' => 'form-control']) !!}
            @if($errors->has('I18N['.$locale->lang.'][description]'))
                <div class="alert-danger">
                    {!! $errors->first('I18N['.$locale->lang.'][description]') !!}
                </div>
            @endif
        </div>
    @endforeach
@endif

<!-- Email Field -->
<div class="form-group">
    {!! Form::label('price', 'Price:') !!}
    {!! Form::text('price', null, ['class' => 'form-control']) !!}
    @if($errors->has('price'))
        <div class="alert-danger">
            {!! $errors->first('price') !!}
        </div>
    @endif
</div>
<!-- Email Field -->
<div class="form-group">
    {!! Form::label('popular_dish', ' Popular dish') !!}
    {!! Form::checkbox('popular_dish', 1, $menuItem->popular_dish) !!}
    @if($errors->has('popular_dish'))
        <div class="alert-danger">
            {!! $errors->first('popular_dish') !!}
        </div>
    @endif
</div>

<hr/>
<div class="form-group">

    {!! Form::label('imge', 'Image:') !!}
    <br/>
    @if ($menuItem->image)
        <img data-upload-img="1" src="{{url('file/resize', [100, $menuItem->image->filename])}}" alt=""
             class="img-circle" width="100"/>
    @else
        <img data-upload-img="1" src="" alt="" class="img-circle" width="100"/>
    @endif
    <br/>
    <br/>

    {!! Form::hidden('image', null, ['class' => 'form-control', 'data-uploaded-field' => 1, 'disabled' => 'disabled']) !!}
    <input type="file" id="image"/>

    @if($errors->has('image'))
        <div class="alert-danger">
            {!! $errors->first('image') !!}
        </div>
    @endif

</div>