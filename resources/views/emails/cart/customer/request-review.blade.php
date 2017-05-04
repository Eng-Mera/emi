@extends('emails.layouts.main')

@section('contentheader_title')
{{ trans('Ask for Review') }}
@stop

@section('main-content')
<div class="box">
    <div class="box-body">
        <p>
            {{ trans('Dear') }} <?= $user->name ?>,
        </p>

        <p>
            {{ trans('Thank you for your recent visit to our restaurant. We want to provide you with the best service
            possible. Would you consider posting a review of us online? In addition to providing feedback, online
            reviews can help other customers learn about who we are & about the services we offer.') }}
        </p>

        <p>
            {{ trans('Please take a minute to leave a review on our restaurant - we would really appreciate it!')
            }}
        </p>

        <p>
            <a href="{{ env('FRONT_URL') }}restaurants/{{ $restaurant->slug }}">Review {{ $restaurant->name }} </a>
        </p>

        <p>
            {{ trans('Thank you, in advance, for your review & for your patronage!') }}.
        </p>

        <p>
            {{ trans('Sales Team') }}
        </p>

    </div>
</div>
@stop