
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
                    <div class="panel-heading text-center">Twitter</div>

                    <div class="panel-body">
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; users/show </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; users/lookup </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; search/tweets </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; followers/ids </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; geo/search </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /users/user-id </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; friendships/lookup </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; friendships/show </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; geo/id/:place_id </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; lists/memberships </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; lists/show  </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; lists/statuses </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; trends/available </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; trends/closest </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp;  users/search </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp;  users/suggestions </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp;users/suggestions/:slug/members</h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; statuses/user_timeline </h3>
                        @include('dummy')

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
