
<div class="form-group">
    {!! Form::label('report_type', 'Report Type:') !!}
    {!! Form::select('report_type', $types, null, ['class' => 'form-control']) !!}
    @if($errors->has('report_type'))
        <div class="alert-danger">
            {!! $errors->first('report_type') !!}
        </div>
    @endif
</div>

<div class="form-group">
    {!! Form::label('report_subject', 'Subjects:') !!}
    {!! Form::select('report_subject', $subjects, null, ['class' => 'form-control']) !!}
    @if($errors->has('report_subject'))
        <div class="alert-danger">
            {!! $errors->first('report_subject') !!}
        </div>
    @endif
</div>


<div class="form-group">
    {!! Form::label('reported_id','Reported : ') !!}
    <select name="reported_id" id="reported_id" class="form-control">

    </select>
    @if($errors->has('reported_id'))
        <div class="alert-danger">
            {!! $errors->first('reported_id') !!}
        </div>
    @endif
</div>

<div class="form-group">
    {!! Form::label('details', 'Details:') !!}
    {!! Form::textarea('details', null, ['class' => 'form-control']) !!}
    @if($errors->has('details'))
        <div class="alert-danger">
            {!! $errors->first('details') !!}
        </div>
    @endif
</div>

<hr/>

@section('scripts')
    <script>
        $('#report_type').on('change', function(e)
        {
            var type = e.target.value;
            if (type == 0)
            {
                type = 'Restaurant';
            }
            else if (type == 1)
            {
                type = 'Review';
            }
            else if (type == 2)
            {
                type = 'Photo';
            }


            $.ajax({
                url: '/admin/report/reported/'+type,
                type: 'GET',
                dataType: 'JSON',
                success: function (data) {
                    console.log(data);
                    //success data
                    $('#reported_id').empty();

                    $('#reported_id').append('<option value=""> Please choose one</option>');

                    $.each(data, function(index, reportedObj){
                        if (type == 'Restaurant')
                        {
                            $('#reported_id').append('<option value="'+reportedObj.id+'">' + reportedObj.name + '</option>');

                        }
                        else if (type == 'Review')
                        {
                            $('#reported_id').append('<option value="'+reportedObj.id+'">' + reportedObj.title + '</option>');
                        }
                        else if  (type == 'Photo')
                        {

                        }


                    });

                }
            });


        });
    </script>

@endsection
