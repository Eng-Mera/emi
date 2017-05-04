@extends('layouts.backend')

@section('contentheader_title')
    Home
@endsection

@section('htmlheader_title')
    Home
@endsection

@section('main-content')
    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Home</div>

                    <div class="panel-body">
                        You are logged in!

                        @is_manager_no_restaurant()
                        <hr/>
                        <p class="alert alert-warning">
                        {{  Lang::get('You don\'t have any restaurant to manage' ) }}
                        </p>
                        @end_is_manager
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
