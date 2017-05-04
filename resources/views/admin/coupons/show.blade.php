@extends('layouts.backend')

@section('htmlheader_title')
Coupon # {{$coupon->code}}
@stop

@section('contentheader_title')
Coupon # {{$coupon->code}}
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
# {{$coupon->code}}
@endsection

@section('main-content')
<div id="welcome">
    @if(!empty($text))
    <div class="container">{!! $text !!}</div>
    @endif
    <div class="container">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table table-striped">
                    <tbody><tr>
                            <th>Item</th>
                            <th>Value</th>
                        </tr>
                        <tr>
                            <td>{{ trans('id') }}</td>
                            <td>
                                {{$coupon->id}}
                            </td>
                        </tr>
                        <tr>
                            <td>{{ trans('code') }}</td>
                            <td>
                                {{$coupon->code}}
                            </td>
                        </tr>
                        <tr>
                            <td>{{ trans('value') }}</td>
                            <td>
                                {!! $value !!}
                            </td>
                        </tr>
                        <tr>
                            <td>{{ trans('type') }}</td>
                            <td>
                                {{trans($coupon->type)}}
                            </td>
                        </tr>
                        <tr>
                            <td>{{ trans('is reusable?') }}</td>
                            <td>
                                {!! $reusable !!}
                            </td>
                        </tr>
                        <tr>
                            <td>{{ trans('user_id') }}</td>
                            <td>
                                {!! $user !!}
                            </td>
                        </tr>
                        <tr>
                            <td>{{ trans('expired at') }}</td>
                            <td>
                                {{ $coupon->expired_at }}
                            </td>
                        </tr>
                        <tr>
                            <td>{{ trans('created at') }}</td>
                            <td>
                                {{ $coupon->created_at }}
                            </td>
                        </tr>
                        <tr>
                            <td>{{ trans('updated at') }}</td>
                            <td>
                                {{ $coupon->updated_at }}
                            </td>
                        </tr>
                    </tbody></table>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
@stop