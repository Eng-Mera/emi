@extends('layouts.backend')

@section('htmlheader_title')
    Update {{ $menuItem->name }}
@endsection

@section('contentheader_title')
    {{ $menuItem->name }}
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/restaurant/'.$restaurant_slug.'/menu-item') }}"><i class="fa fa-dashboard"></i>Menu Item</a></li>
@endsection

@section('breadcrumb_current')
    Update Menu Item
@endsection

@section('main-content')
    <div class="box">
        <div class="box-body">

            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#menuitem" aria-controls="home" role="tab" data-toggle="tab">Menu Item Details</a></li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="menuitem">
                    @include('admin.menuitem.partials.edit-menuitem')
                </div>
             </div>

        </div>
    </div>
@stop