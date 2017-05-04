@extends('layouts.backend')

@section('htmlheader_title')
    Coupons
@stop

@section('contentheader_title')
    Coupons
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin') }}"><i class="fa fa-dashboard"></i>Coupon</a>
    </li>
@endsection

@section('breadcrumb_current')
    Coupon
@endsection

@section('main-content')
    <div id="welcome">

        @if(!empty($text))
            <div class="container">{!! $text !!}</div>
        @endif
            <style>
                /*#example_grid1 td {*/
                    /*!*white-space: nowrap;*!*/
                /*}*/
            </style>
            <?= $grid ?>
    </div>
@stop