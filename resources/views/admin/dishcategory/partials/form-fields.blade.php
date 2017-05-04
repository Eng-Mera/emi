@if(!empty($category->translate()))
    @foreach ($locales as $locale)
        <div class="form-group">
            {!! Form::label('I18N['.$locale->lang.'][category_name]', 'Category name '.$locale->lang.':') !!}
            @if(!empty($category->translate($locale->lang)))
            {!! Form::text('I18N['.$locale->lang.'][category_name]', $category->translate($locale->lang)->category_name , ['class' => 'form-control']) !!}
            @else
            {!! Form::text('I18N['.$locale->lang.'][category_name]', '' , ['class' => 'form-control']) !!}
            @endif
            @if($errors->has('I18N['.$locale->lang.'][category_name]'))
                <div class="alert-danger">
                    {!! $errors->first('I18N['.$locale->lang.'][category_name]') !!}
                </div>
            @endif
        </div>
    @endforeach
@else
    @foreach ($locales as $locale)
        <div class="form-group">
            {!! Form::label('I18N['.$locale->lang.'][category_name]', 'Category name '.$locale->lang.':') !!}
            {!! Form::text('I18N['.$locale->lang.'][category_name]', null , ['class' => 'form-control']) !!}
            @if($errors->has('I18N['.$locale->lang.'][category_name]'))
                <div class="alert-danger">
                    {!! $errors->first('I18N['.$locale->lang.'][category_name]') !!}
                </div>
            @endif
        </div>
    @endforeach
@endif