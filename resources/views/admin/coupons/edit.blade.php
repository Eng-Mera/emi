@extends('layouts.backend')

@section('htmlheader_title')
    {{ trans('New Coupon') }} # {{ $coupon->code }}
@stop

@section('contentheader_title')
    {{ trans('Edit Coupon') }} # {{ $coupon->code }}
@stop

@section('breadcrumb_parent')
    <li>
        <a href="{{ url('admin') }}"><i class="fa fa-dashboard"></i>Admin</a>
    </li>
    <li>
        <a href="{{ url('admin/coupon') }}">Coupon</a>
    </li>
@endsection

@section('breadcrumb_current')
    {{ trans('Edit Coupon') }} # {{ $coupon->code }}
@endsection

@section('main-content')
    <div id="welcome">
        @if(!empty($text))
            <div class="container">{!! $text !!}</div>
        @endif
        @include('admin.coupons.partials.form')
    </div>
@stop