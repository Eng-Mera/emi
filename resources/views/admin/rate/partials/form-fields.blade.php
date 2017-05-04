<!-- Email Field -->
<div class="form-group">
    {!! Form::label('last_visit_date', 'Last Visit Date:') !!}
    {!! Form::text('last_visit_date', $rate->last_visit_date, ['class' => 'form-control', 'data-provide' => "datepicker", 'data-date-format' => "yyyy-mm-dd"]) !!}

    @if($errors->has('last_visit_date'))
        <div class="alert-danger">
            {!! $errors->first('last_visit_date') !!}
        </div>
    @endif

</div>
<hr/>
<h4>Review</h4>

<div class="form-group">
    {!! Form::label('title', 'Title:') !!}
    {!! Form::text('title', $rate->title, ['class' => 'form-control']) !!}
    @if($errors->has('title'))
        <div class="alert-danger">
            {!! $errors->first('title') !!}
        </div>
    @endif
</div>

<div class="form-group">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::textarea('description', $rate->description, ['class' => 'form-control']) !!}
    @if($errors->has('description'))
        <div class="alert-danger">
            {!! $errors->first('description') !!}
        </div>
    @endif
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <th>Rate</th>
        <th>Value</th>
        </thead>
        @foreach($rate->rate as $rat)

            <tr>
                <td>
                    {!! Form::label('type', $rate_types[$rat->type]) !!}
                </td>
                <td>
                    <div class="col-md-4">
                        {!! Form::select('rate_value[]', $rate_values, $rat->rate_value, ['class' => 'form-control']) !!}
                        {!! Form::hidden('type[]', $rat->type) !!}
                        @if($errors->has('rate_value'))
                            <div class="alert-danger">
                                {!! $errors->first('rate_value') !!}
                            </div>
                        @endif
                    </div>

                </td>
            </tr>
    @endforeach
</div>
</div>
</table>
</div>