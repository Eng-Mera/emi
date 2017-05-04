@extends('emails.layouts.main')

@section('contentheader_title')
    {{ trans('Order Change was Requested') }}
@stop

@section('main-content')
    <div class="box">
        <div class="box-body">
            <p>
                {{ trans('Dear') }} <?= $user->name ?>,
            </p>

            <p>
                {{ trans('A change for reservation ') }} #{{$reservation['id']}} {{ trans('was requested.') }}
            </p>

            <p>
                {{trans('Customer Details:')}}
            </p>

            <p>
                <span>{{trans('Name')}}</span> {{$customer['name']}}
            </p>

            <p>
                <span>{{trans('E-mail')}}</span> {{$customer['email']}}
            </p>

            <p>
                <span>{{trans('Mobile')}}</span> {{$customer['phone']}}
            </p>

            <p>
                {{trans('Change Details:')}}
            </p>

            @if (!empty($reservation->changes))
                @foreach($reservation->changes  as $change)
                    <p>
                        {{$change->attribute}} : {{$change->value}}
                    </p>
                @endforeach
            @endif

            <p>
                {{ trans('With best regards,') }}
            </p>

            <p>
                {{ trans('Sales Team') }}
            </p>

        </div>
    </div>
@stop