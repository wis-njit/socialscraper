@extends('layouts.app')

@section('content')
    <!-- BEGIN DOCUMENTATION -->
    <!--
                           -->
    <!-- END DOCUMENTATION -->
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="row">
                        <div class="panel-heading text-center">
                            <a class="col-md-4" href="{{ url('/user/facebook') }}">Facebook Graph API</a>
                            <a class="col-md-4" href="{{ url('/user/instagram') }}">Instagram API</a>
                            <a class="col-md-4" href="{{ url('/user/twitter') }}">Twitter API</a>
                        </div>
                    </div>
                    <div class="panel-heading text-center">Facebook</div>
                    <div class="panel-body">
                      <h3> <span class="get">&nbsp;GET&nbsp;</span>&nbsp;/{user-id}/friendlists </h3>
                        @include('dummy')

                        <h3> <span class="get">&nbsp;GET&nbsp;</span>&nbsp; /{post-id} </h3>
                        @include('dummy')

                        <h3> <span class="get">&nbsp;GET&nbsp;</span>&nbsp;/{comment-id} </h3>
                        @include('dummy')

                        <h3> <span class="get">&nbsp;GET&nbsp;</span>&nbsp;/{group-id} </h3>
                        @include('dummy')

                        <h3> <span class="get">&nbsp;GET&nbsp;</span>&nbsp;/{group-id}/members </h3>
                        @include('dummy')

                        <h3> <span class="get">&nbsp;GET&nbsp;</span>&nbsp;/{object-id}/likes </h3>
                        @include('dummy')

                        <h3> <span class="get">&nbsp;GET&nbsp;</span>&nbsp;/{group-id}/feed </h3>
                        @include('dummy')

                        <h3> <span class="get">&nbsp;GET&nbsp;</span>&nbsp;/{work-experience-id} </h3>
                        @include('dummy')

                        <h3> <span class="get">&nbsp;GET&nbsp;</span>&nbsp;/{education-experience-id} </h3>
                        @include('dummy')

                        <h3> <span class="get">&nbsp;GET&nbsp;</span>&nbsp;/{user-id} </h3>
                        @include('dummy')

                        <h3> <span class="get">&nbsp;GET&nbsp;</span>&nbsp;/{offer_id}  </h3>
                        @include('dummy')

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
