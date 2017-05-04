
<h4>Reply</h4>



<div class="form-group">
    {!! Form::label('title', 'Title:') !!}
    {!! Form::text('title', $reply->title, ['class' => 'form-control']) !!}
    @if($errors->has('title'))
        <div class="alert-danger">
            {!! $errors->first('title') !!}
        </div>
    @endif
</div>

<div class="form-group">
    {!! Form::label('comment', 'Comment:') !!}
    {!! Form::textarea('comment', $reply->Comment, ['class' => 'form-control']) !!}
    @if($errors->has('comment'))
        <div class="alert-danger">
            {!! $errors->first('comment') !!}
        </div>
    @endif
</div>

