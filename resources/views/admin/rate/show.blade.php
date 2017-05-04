@extends('layouts.backend')

@section('htmlheader_title')
    Review
@endsection

@section('contentheader_title')
    Review
@stop

@section('breadcrumb_parent')
    <li><a href="{{ url('admin/restaurant') }}"><i class="fa fa-dashboard"></i>Restaurant</a></li>
@endsection

@section('breadcrumb_current')
    Show Restaurant
@endsection

@section('main-content')

    <pre>{{ json_encode($rate, JSON_PRETTY_PRINT) }};</pre>
    <br>
    <h2>Replies</h2>

    <ul class="timeline">

        @foreach($reply as $replyReview)
            <?php //var_dump($test); ?>
            <!-- timeline item -->
            <li>
                <div class="timeline-item">

                    <h3 class="timeline-header"><a href="#"><?php echo $replyReview->title; ?></a> ...</h3>

                    <div class="timeline-body">
                        <?php echo $replyReview->comment; ?>
                    </div>

                    <div class="timeline-footer">
                        <a class="btn btn-primary"
                           href=" {{ url('admin/reply-review/'.$review_id. '/' . $replyReview->id .'/edit') }}">Edit</a>
                        <a class="btn btn-danger delete-action" title="Delete" href="#">Delete</a>
                    </div>
                    <form action="{{ url('admin/reply-review/'.$review_id. '/' . $replyReview->id ) }}" method="POST">
                        <input type="hidden" name="_method" value="DELETE"/>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </form>
                </div>
            </li>
            <!-- END timeline item -->

        @endforeach

    </ul>


@stop