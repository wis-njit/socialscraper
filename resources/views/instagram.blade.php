

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
                    <div class="panel-heading text-center">Instagram</div>
                    <div class="panel-body">
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /users/self/follows </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /users/self/followed-by </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /users/self/requested-by </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /users/user-id/relationship </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /users/self </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /users/user-id </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /media/media-id </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; user/self/media/liked </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /media/media-id/ </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /media/media-id/likes </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /locations/location-id  </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /locations/location-id/media/recent </h3>
                        @include('dummy')

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
