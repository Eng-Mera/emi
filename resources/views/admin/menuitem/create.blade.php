@extends('layouts.backend')

@section('htmlheader_title')
    Create New Menu Item
@endsection

@section('contentheader_title')
    Create New Menu Item
@endsection

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/restaurant/'.$restaurant_slug.'/menu-item') }}"><i class="fa fa-dashboard"></i>Menu Items</a>
    </li>
@endsection

@section('breadcrumb_current')
    Create Menu Items
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">

            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#restaurant" aria-controls="home" role="tab" data-toggle="tab">Restaurant Details</a></li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="restaurant">
                    @include('admin.menuitem.partials.create-menuitem')
                </div>
            </div>

        </div>
    </div>
@stop