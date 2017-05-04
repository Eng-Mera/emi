@extends('emails.layouts.main')

@section('contentheader_title')
    {{ trans('New Job Seeker') }}
@stop

@section('main-content')
    <div class="box">
        <div class="box-body">
            <p>
                {{ trans('Dear') }} <?php echo $manager->name ?>,
            </p>
            <br/>
            A new job seeker applied for your job!
            <br/>
            <br/>
            <?php echo $user->name; ?>
            <br/>
            <br/>
        </div>
    </div>
@stop