<h1>Profile</h1>

{!!  Form::model(@$user->profile, ['method' => 'PATCH', 'route' => ['admin.user.update', $user->username]]) !!}

<div class="form-group">

    {!! Form::label('Mobile', 'Mobile:') !!}
    {!! Form::number('profile[mobile]', @$user->profile->mobile, ['class' => 'form-control']) !!}

    @if($errors->has('mobile'))
        <div class="alert-danger">
            {!! $errors->first('mobile') !!}
        </div>
    @endif

</div>

<div class="form-group">
    {!! Form::label('address', 'Address:') !!}
    {!! Form::text('profile[address]', @$user->profile->address, ['class' => 'form-control']) !!}

    @if($errors->has('address'))
        <div class="alert-danger">
            {!! $errors->first('address') !!}
        </div>
    @endif
</div>

<!-- Bio Field -->
<div class="form-group">
    {!! Form::label('qualification', 'Qualification:') !!}
    {!! Form::textarea('profile[qualification]', @$user->profile->qualification, ['class' => 'form-control']) !!}
    @if($errors->has('qualification'))
        <div class="alert-danger">
            {!! $errors->first('qualification') !!}
        </div>
    @endif
</div>

<div class="row">
    <div class="col-md-6">

        <div class="form-group">
            {!! Form::label('current_employee', 'Current Employee:') !!}
            {!! Form::text('profile[current_employee]', @$user->profile->current_employee, ['class' => 'form-control']) !!}

            @if($errors->has('current_employee'))
                <div class="alert-danger">
                    {!! $errors->first('current_employee') !!}
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('current_position', 'Current Position:') !!}
            {!! Form::text('profile[current_position]', @$user->profile->current_position, ['class' => 'form-control']) !!}

            @if($errors->has('current_position'))
                <div class="alert-danger">
                    {!! $errors->first('current_position') !!}
                </div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">

        <div class="form-group">
            {!! Form::label('previous_employee', 'Previous Employee:') !!}
            {!! Form::text('profile[previous_employee]', @$user->profile->previous_employee, ['class' => 'form-control']) !!}

            @if($errors->has('previous_employee'))
                <div class="alert-danger">
                    {!! $errors->first('previous_employee') !!}
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('previous_position', 'Previous Position:') !!}
            {!! Form::text('profile[previous_position]', @$user->profile->previous_position, ['class' => 'form-control']) !!}

            @if($errors->has('previous_position'))
                <div class="alert-danger">
                    {!! $errors->first('previous_position') !!}
                </div>
            @endif
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label('experience_years', 'Experience Years:') !!}
    {!! Form::number('profile[experience_years]', @$user->profile->experience_years, ['class' => 'form-control']) !!}

    @if($errors->has('experience_years'))
        <div class="alert-danger">
            {!! $errors->first('experience_years') !!}
        </div>
    @endif
</div>


<div class="form-group">
    {!! Form::label('current_salary', 'Current Salary:') !!}
    {!! Form::number('profile[current_salary]', @$user->profile->current_salary, ['class' => 'form-control']) !!}

    @if($errors->has('current_salary'))
        <div class="alert-danger">
            {!! $errors->first('current_salary') !!}
        </div>
    @endif
</div>

<div class="form-group">
    {!! Form::label('expected_salary', 'Expected Salary:') !!}
    {!! Form::number('profile[expected_salary]', @$user->profile->expected_salary, ['class' => 'form-control']) !!}

    @if($errors->has('expected_salary'))
        <div class="alert-danger">
            {!! $errors->first('current_salary') !!}
        </div>
    @endif
</div>

<!-- Update Profile Field -->
<div class="form-group">
    {!! Form::submit('Update Profile', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}