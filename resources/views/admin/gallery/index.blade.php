@extends('layouts.backend')

@section('htmlheader_title')
    Gallery
@stop

@section('contentheader_title')
    Gallery
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/restaurant/'.$restaurant_slug.'/gallery') }}"><i class="fa fa-dashboard"></i>Gallery</a>
    </li>
@endsection

@section('breadcrumb_current')
    View Gallery
@endsection

@section('main-content')
    <div class="container" style="text-align: center">
        <div class="col-md-11">
            <div class="panel panel-default">

                <div class="panel-body">

                @if($gallery->file->count())
                    <!-- Wrapper for slides -->
                        <div id="links" class="links">
                            @foreach($gallery->file as  $i=>$galleryItem)

                                <div class="img-controller">
                                    <a title="Delete" href="#"><span
                                                class="delete-action glyphicon glyphicon-remove"></span></a>

                                    <form action="{{ url('admin/restaurant/'.$restaurant_slug.'/gallery/'. $galleryItem->id) }}"
                                          method="POST">
                                        <input type="hidden" name="_method" value="DELETE"/>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    </form>

                                    <a href="{{ $galleryItem->image_url }}?=rand=<?php echo rand(22222,9999999) ?>" title="{{ @$galleryItem->meta->title }}"
                                       data-gallery>
                                        <img src="{{ $galleryItem->image_url }}" alt="{{ @$galleryItem->meta->title  }}">
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <!-- The Gallery as lightbox dialog, should be a child element of the document body -->
                        <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-start-slideshow="true"
                             >
                            <div class="slides"></div>
                            <h3 class="title"></h3>
                            <a class="prev">‹</a>
                            <a class="next">›</a>
                            <a class="close">×</a>
                            <a class="play-pause"></a>
                            <ol class="indicator"></ol>
                        </div>
                    @else
                        No images has been added to the restaurant gallery yet.
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop