@extends('layouts.app')

@section('content')
    <!--
         Template for Facebook API calls (/user/Facebook)
         Contains various GETs and POST for the Facebook API
         User must have linked their SNS account to SS (Social Scraper)
         on the dashboard page (lvh.me:8000/home)
     -->

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="row">
                        <div class="panel-heading text-center">
                            <!-- $accounts is passed to this view from FacebookController.php -->
                            @foreach($accounts as $account)

                                @if($account->name !== 'google')
                                    @if($account->active === 1)
                                        <!-- Display a link to the API page for another SNS in SS -->
                                        <a class="col-md-4" href="{{ url('/user/' . $account->name) }}">{{ucfirst($account->name)}} API</a>
                                    @else
                                        <!-- Appears as Not Available for Unlinked SNS -->
                                        <a href="{{url('/user/profile')}}" class="col-md-4"> {{ucfirst($account->name)}} API Not available </a>
                                    @endif

                                @endif
                            @endforeach
                        </div>
                    </div>
                    <!--
                        BEGIN POST REQ
                        Currently in development:
                        Form to make a post to Facebook API.
                        Results are displayed in the div below
                        Test accounts must be created through the Facebook Developers page.
                    -->

                    <div class="panel-heading text-center">Facebook</div>
                    <div class="panel-body">
                        <h3> <span class="post">&nbsp;POST&nbsp;</span>&nbsp;/{test-user-id}/ </h3>
                        <!--
                            Makes a POST to Facebook API to update the userInfo for a test User.
                            Cannot use real user accounts until the application passes Facebook's
                            Application Process.
                        -->
                        {!! Form::open(array('action' => 'FacebookController@updateUserInfo')) !!}
                        {!! Form::token() !!}
                        <div class="row">
                            <div class="col-md-2">
                                {!! Form::label('test_user_id', 'Parameter: {test-user-id}', ['class' => 'control-label pull-right']) !!}
                            </div>
                            <div class="col-md-10">
                                {!! Form::text('test_user_id','',['class' => 'form-control pull-left']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                {!! Form::label('name', 'Parameter: name', ['class' => 'control-label pull-right']) !!}
                            </div>
                            <div class="col-md-10">
                                {!! Form::text('name','',['class' => 'form-control pull-left']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                {!! Form::label('password', 'Parameter: password', ['class' => 'control-label pull-right']) !!}
                            </div>
                            <div class="col-md-10">
                                {!! Form::text('password','',['class' => 'form-control pull-left']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                {{ Form::submit('Run Query', ['id' => 'btn-submit', 'class' => 'btn btn-info pull-left' ]) }}
                                {{ Form::close() }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h1> Results</h1>
                            </div>
                        </div>

                        <h3> <span class="post">&nbsp;POST&nbsp;</span>&nbsp;/{test-user-1}/friends/{test-user-2} </h3>
                        <!--
                            Makes a POST to Facebook API to send a friend request from test-user-1 to test-user-2
                        -->
                        {!! Form::open(array('action' => 'FacebookController@friendRequest')) !!}
                        {!! Form::token() !!}
                        <div class="row">
                            <div class="col-md-2">
                                {!! Form::label('test_user_1', 'Parameter: {test-user-1}', ['class' => 'control-label pull-right']) !!}
                            </div>
                            <div class="col-md-10">
                                {!! Form::text('test_user_1','',['class' => 'form-control pull-left']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                {!! Form::label('test_user_2', 'Parameter: test-user-2', ['class' => 'control-label pull-right']) !!}
                            </div>
                            <div class="col-md-10">
                                {!! Form::text('test_user_2','',['class' => 'form-control pull-left']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                {{ Form::submit('Run Query', ['id' => 'btn-submit', 'class' => 'btn btn-info pull-left' ]) }}
                                {{ Form::close() }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h1> Results</h1>
                            </div>
                        </div>
                        <!-- END POST FORMS -->

                        <!-- BEGIN GET FORMS -->
                        <!--
                            These GET calls are all made on page load
                            Various GET calls to Facebook API
                            Output is displayed with Prettified JSON
                            Some outputs are spooofed because Facebook does not allow
                            sandboxed applications to access certain authorization
                            'scopes'.
                         -->
                        <h3> <span class="get">&nbsp;GET&nbsp;</span>&nbsp;/{user-id}/friendlists </h3>
                        <!-- Issue: The portion below needs to be implemented in a DRY manner (don't repeat yourself) -->
                        <script src="/js/prettify.js">

                        </script>
                        <!-- Takes a response object and prettifies it, then outputs it as a PRE -->
                        <script>
                            var test = {!! $responses['userid_friends'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!-- Gets the current user's friends -->
                        <h3> <span class="get">&nbsp;GET&nbsp;</span>&nbsp;/{user-id}/friends </h3>
                        <!-- dummy JSON -->
                        @include('dummy')

                        <!-- Gets a information about a post -->
                        <h3> <span class="get">&nbsp;GET&nbsp;</span>&nbsp; /{post-id} </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['postid'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!-- Gets a comment by its comment ID -->
                        <h3> <span class="get">&nbsp;GET&nbsp;</span>&nbsp;/{comment-id} </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['commentid'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!-- Gets a group by its group-id -->
                        <h3> <span class="get">&nbsp;GET&nbsp;</span>&nbsp;/{group-id} </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['groupid'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!-- Gets the members of a group by its ID -->
                        <h3> <span class="get">&nbsp;GET&nbsp;</span>&nbsp;/{group-id}/members </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['groupid_members'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!--
                            This reference describes the /likes edge that is common to multiple Graph API nodes.
                            The structure and operations are the same for each node.
                        -->
                        <h3> <span class="get">&nbsp;GET&nbsp;</span>&nbsp;/{object-id}/likes </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['objectid_likes'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <h3> <span class="get">&nbsp;GET&nbsp;</span>&nbsp;/{group-id}/feed </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['groupid_feed'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!-- Get a user's work experience -->
                        <h3> <span class="get">&nbsp;GET&nbsp;</span>&nbsp;/{work-experience-id} </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['workexperienceid'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!-- Get a user's education experience -->
                        <h3> <span class="get">&nbsp;GET&nbsp;</span>&nbsp;/{education-experience-id} </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['educationexperienceid'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!-- Get general information about a user -->
                        <h3> <span class="get">&nbsp;GET&nbsp;</span>&nbsp;/{user-id} </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['userid'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>



                        <h3> <span class="get">&nbsp;GET&nbsp;</span>&nbsp;/{offer_id}  </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['offerid'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
