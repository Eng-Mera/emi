<div class="form-group">
    {!! Form::label('name', 'Item name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
    @if($errors->has('name'))
        <div class="alert-danger">
            {!! $errors->first('name') !!}
        </div>
    @endif
</div>

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

<!-- Password Field -->
<div class="form-group">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
    @if($errors->has('description'))
        <div class="alert-danger">
            {!! $errors->first('description') !!}
        </div>
    @endif
</div>

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
    {!! Form::checkbox('popular_dish', 1, $menuitem->popular_dish) !!}
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
    @if ($menuitem->image)
        <img data-upload-img="1" src="{{url('file/resize', [100, $menuitem->image->filename])}}" alt=""
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