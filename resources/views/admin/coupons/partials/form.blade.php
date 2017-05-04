
@if($errors->has())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            {{ $error }}<>
        @endforeach
    </div>
@endif
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">
            {{ trans('Enter Coupon Details') }}
        </h3>

        <div class="box-tools pull-right">
        </div>
    </div>
    <div class="box-body">

        @if(isset($coupon))
            {!! Form::model($coupon, ['method'=>'POST', 'route'=>['update_coupon', 'id'=>$coupon->id]]) !!}
            {!! Form::hidden('_method', 'PATCH') !!}
        @else
            {!! Form::open(['method'=>'POST', 'route'=>'store_coupon']) !!}
        @endif

        <div class="form-group">
            {!!
            Form::label('code', trans('Code'), [
            'class' => 'control-label'
            ]
            )
            !!}
            {!! Form::text('code', null, [
            'class' => 'form-control'
            ]) !!}
        </div>
            {!! Form::hidden('user_id', $coupon->user->id) !!}

        <div class="form-group">
            {!!
            Form::label('type', trans('Type'), [
            'class' => 'control-label'
            ]
            )
            !!}
            {!! Form::select('type', $coupon_types, null, [
            'placeholder' => 'Pick a type...',
            'class' => 'form-control'
            ]) !!} <!-- View Composer -->
        </div>

        <div class="form-group">
            {!!
            Form::label('value', trans('Value'), [
            'class' => 'control-label'
            ]
            )
            !!}
            <div class="input-group">
                <span class="input-group-addon">%</span>
                {!! Form::number('value', null, [
                'class' => 'form-control',
                'step' => 'any'
                ]) !!}
            </div>
        </div>

        <div class="form-group">
            {!!
            Form::label('reusable', trans('Reusable'), [
            'class' => 'control-label'
            ]
            )
            !!}
            <div class="icheckbox_flat-green">
                {!! Form::checkbox('reusable', 1, false, [
                'class' => 'flat-red'
                ]) !!}
            </div>
        </div>

        <div class="form-group">
            {!!
            Form::label('expired_at', trans('Expires at'), [
            'class' => 'control-label'
            ]
            )
            !!}
            <div class="input-group date">

                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                {!! Form::text('expired_at', null,[
                'id' => 'datepicker',
                'class' => 'form-control'
                ]) !!} <!-- Get from Config -->
            </div>
        </div>



        <div class="box-footer">
            {!! Form::submit(trans('Save'), [
            'class' => 'btn btn-primary'
            ]) !!}

            {!! Form::submit(trans('Save & Add Another'), [
            'class' => 'btn btn-primary'
            ]) !!}
        </div>

        {!! Form::token() !!}

        {!! Form::close() !!}
    </div>
</div>

