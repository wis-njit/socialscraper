

@extends('layouts.app')

@section('content')
    <!--
         Template for Instagram API calls (/user/instagram)
         Contains various GETs and POST for the Instagram API
         User must have linked their SNS account to SS (Social Scraper)
         on the dashboard page (lvh.me:8000/home)
     -->

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="row">
                        <div class="panel-heading text-center">
                            <!-- $accounts is passed to this view from InstagramController.php -->
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
                    <div class="panel-heading text-center">Instagram</div>
                    <!--
                        BEGIN POST FORMS
                        Currently in development:
                        Form to make a post to Facebook API.
                        Results are displayed in the div below
                        Users must be authorized "Sandbox" Users
                        See: https://www.instagram.com/developer/sandbox/
                    -->
                    <div class="panel-body">
                        <h3> <span class="post">&nbsp;POST&nbsp;</span>&nbsp;/media/{media-id}/likes </h3>
                        <!-- Set a like on {media-id} by the current user -->
                        {!! Form::open(array('action' => 'InstagramController@like')) !!}
                        {!! Form::token() !!}
                        <div class="row">
                            <div class="col-md-2">
                                {!! Form::label('media_id', 'Parameter: {media-id}', ['class' => 'control-label pull-right']) !!}
                            </div>
                            <div class="col-md-10">
                                {!! Form::text('media_id','',['class' => 'form-control pull-left']) !!}
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
                        <h3> <span class="post">&nbsp;POST&nbsp;</span>&nbsp;/users/{user-id}/relationship </h3>
                        <!--
                             Modify the relationship between the current user and {user-id}
                             Valid actions: Follow, Unfollow, Approve, Ignore
                         -->
                        {!! Form::open(array('action' => 'InstagramController@modifyRelationship')) !!}
                        {!! Form::token() !!}
                        <div class="row">
                            <div class="col-md-2">
                                {!! Form::label('user_id', 'Parameter: {user-id}', ['class' => 'control-label pull-right']) !!}
                            </div>
                            <div class="col-md-10">
                                {!! Form::text('user_id','',['class' => 'form-control pull-left']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                {!! Form::label('action', 'Parameter: {action}', ['class' => 'control-label pull-right']) !!}
                            </div>
                            <div class="col-md-10">
                                {!! Form::select('action', array('follow' => 'Follow', 'unfollow' => 'Unfollow', 'approve' => 'Approve', 'ignore' => 'Ignore'), 'follow') !!}
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
                        <!-- END POST REQ -->

                        <!-- BEGIN GET REQ -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /users/self/follows </h3>
                        <!-- Issue: The portion below needs to be implemented in a DRY manner (don't repeat yourself) -->
                        <pre> {!! $responses['users_self_follows'] !!}</pre>

                        <!-- Get the list of users this user is followed by -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /users/self/followed-by </h3>
                        <pre> {!! $responses['users_self_followedby'] !!} </pre>

                        <!-- List the users who have requested this user's permission to follow. -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /users/self/requested-by </h3>
                        <pre> {!! $responses['users_self_requestedby'] !!} </pre>

                        <!-- Get information about a relationship to another user. -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /users/user-id/relationship </h3>
                        <pre> {!! $responses['users_userid_relationship'] !!} </pre>

                        <!-- Get information about the owner of the access_token. -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /users/self </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['users_self'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!--
                           Get information about a user.
                           The public_content scope is required if the user is not the owner of the access_token.
                        -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /users/user-id </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['users_userid'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!--
                            Get information about a media object.
                            Use the type field to differentiate between image and video media in the response.
                            You will also receive the user_has_liked field which tells you whether
                            the owner of the access_token has liked this media.
                        -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /media/media-id </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = "{!! $responses['media_mediaid'] !!}";
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!--
                            Get the list of recent media liked by the owner of the access_token.
                        -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; user/self/media/liked </h3>
                        <pre> {!! $responses['users_self_media_liked'] !!} </pre>

                        <!-- Get a list of users who have liked this media. -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /media/media-id/likes </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = "{!! $responses['media_mediaid_likes'] !!}";
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!-- Get information about a location. -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /locations/location-id  </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = "{!! $responses['locations_locationid'] !!}";
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!-- Get a list of recent media objects from a given location. -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /locations/location-id/media/recent </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = "{!! $responses['locations_locationid_media_recent'] !!}";
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
