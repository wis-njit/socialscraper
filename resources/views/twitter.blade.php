
@extends('layouts.app')

@section('content')
    <!--
         Template for Twitter API calls (/user/twitter)
         Contains various GETs and POST for the Twitter API
         User must have linked their SNS account to SS (Social Scraper)
         on the dashboard page (lvh.me:8000/home)
     -->
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="row">
                        <div class="panel-heading text-center">
                            <!-- $accounts is passed to this view from TwitterController.php -->
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
                    <div class="panel-heading text-center">Twitter</div>
                    <div class="panel-body">
                        <!--
                            Sets some values that users are able to set under the “Account” tab of their settings page.
                            Only the parameters specified will be updated.
                        -->
                        <h3> <span class="post">&nbsp;POST&nbsp;</span>&nbsp;/account/update_profile</h3>
                        {!! Form::open(array('action' => 'TwitterController@updateAccountStatus')) !!}
                        {!! Form::token() !!}
                        <div class="row">
                            <div class="col-md-2">
                                {!! Form::label('name', 'Parameter: {name}', ['class' => 'control-label pull-right']) !!}
                            </div>
                            <div class="col-md-10">
                                {!! Form::text('name','',['class' => 'form-control pull-left']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                {!! Form::label('url', 'Parameter: {url}', ['class' => 'control-label pull-right']) !!}
                            </div>
                            <div class="col-md-10">
                                {!! Form::text('url','',['class' => 'form-control pull-left']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                {!! Form::label('location', 'Parameter: {location}', ['class' => 'control-label pull-right']) !!}
                            </div>
                            <div class="col-md-10">
                                {!! Form::text('location','',['class' => 'form-control pull-left']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                {!! Form::label('description', 'Parameter: {description}', ['class' => 'control-label pull-right']) !!}
                            </div>
                            <div class="col-md-10">
                                {!! Form::text('description','',['class' => 'form-control pull-left']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                {!! Form::label('profile_link_color', 'Parameter: {profile_link_color}', ['class' => 'control-label pull-right']) !!}
                            </div>
                            <div class="col-md-10">
                                {!! Form::text('profile_link_color','',['class' => 'form-control pull-left']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                {!! Form::label('include_entities', 'Parameter: {include_entities}', ['class' => 'control-label pull-right']) !!}
                            </div>
                            <div class="col-md-10">
                                {!! Form::text('include_entities','',['class' => 'form-control pull-left']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                {!! Form::label('skip_status', 'Parameter: {skip_status}', ['class' => 'control-label pull-right']) !!}
                            </div>
                            <div class="col-md-10">
                                {!! Form::text('skip_status','',['class' => 'form-control pull-left']) !!}
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

                        <!--
                            Updates the authenticating user’s current status, also known as Tweeting.
                        -->
                        <h3> <span class="post">&nbsp;POST&nbsp;</span>&nbsp;/statuses/update </h3>
                        {!! Form::open(array('action' => 'TwitterController@updateStatus')) !!}
                        {!! Form::token() !!}
                        <div class="row">
                            <div class="col-md-4">
                                {!! Form::label('status', 'Parameter: {status}', ['class' => 'control-label pull-left']) !!}
                            </div>
                            <div class="col-md-8">
                                {!! Form::text('status','',['class' => 'form-control pull-left']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                {!! Form::label('in_reply_to_status_id', 'Parameter: {in_reply_to_status_id}', ['class' => 'control-label pull-left']) !!}
                            </div>
                            <div class="col-md-8">
                                {!! Form::text('in_reply_to_status_id','',['class' => 'form-control pull-left']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                {!! Form::label('possibly_sensitive', 'Parameter: {possibly_sensitive}', ['class' => 'control-label pull-left']) !!}
                            </div>
                            <div class="col-md-8">
                                {!! Form::text('possibly_sensitive','',['class' => 'form-control pull-left']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                {!! Form::label('lat', 'Parameter: {lat}', ['class' => 'control-label pull-left']) !!}
                            </div>
                            <div class="col-md-8">
                                {!! Form::text('lat','',['class' => 'form-control pull-left']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                {!! Form::label('long', 'Parameter: {long}', ['class' => 'control-label pull-left']) !!}
                            </div>
                            <div class="col-md-8">
                                {!! Form::text('long','',['class' => 'form-control pull-left']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                {!! Form::label('place_id', 'Parameter: {place_id}', ['class' => 'control-label pull-left']) !!}
                            </div>
                            <div class="col-md-8">
                                {!! Form::text('place_id','',['class' => 'form-control pull-left']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                {!! Form::label('display_coordinates', 'Parameter: {display_coordinates}', ['class' => 'control-label pull-left']) !!}
                            </div>
                            <div class="col-md-8">
                                {!! Form::text('display_coordinates','',['class' => 'form-control pull-left']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                {!! Form::label('trim_user', 'Parameter: {trim_user}', ['class' => 'control-label pull-left']) !!}
                            </div>
                            <div class="col-md-8">
                                {!! Form::text('trim_user','',['class' => 'form-control pull-left']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                {!! Form::label('media_ids', 'Parameter: {media_ids}', ['class' => 'control-label pull-left']) !!}
                            </div>
                            <div class="col-md-8">
                                {!! Form::text('media_ids','',['class' => 'form-control pull-left']) !!}
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
                        <!--
                            Returns a variety of information about the user specified by the required user_id or screen_name parameter.
                            The author’s most recent Tweet will be returned inline when possible.
                        -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; users/show </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['users_show'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!--
                            Returns fully-hydrated user objects for up to 100 users per request, as specified by
                            comma-separated values passed to the user_id and/or screen_name parameters.
                        -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; users/lookup </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['users_lookup'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!--
                            Returns a collection of relevant Tweets matching a specified query.
                        -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; search/tweets </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['search_tweets'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!--
                            Returns a cursored collection of user IDs for every user following the specified user.
                        -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; followers/ids </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['followers_ids'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!--
                            Search for places that can be attached to a statuses/update. Given a latitude and a
                            longitude pair, an IP address, or a name, this request will return a list of all the
                            valid places that can be used as the place_id when updating a status.
                        -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; geo/search </h3>

                        <pre>{!! $responses['geo_search'] !!}</pre>


                        <!-- Gets informatoin about a user -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /users/user-id </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['users_userid'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!--
                            Returns the relationships of the authenticating user to the comma-separated list of up to
                            100 screen_names or user_ids provided.
                            Values for connections can be: following, following_requested, followed_by, none, blocking, muting.
                        -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; friendships/lookup </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['friendships_lookup'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!--  Returns detailed information about the relationship between two arbitrary users. -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; friendships/show </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['friendships_show'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!-- Returns all the information about a known place. -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; geo/id/:place_id </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['geo_id_placeid'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!-- Returns the lists the specified user has been added to. If user_id or screen_name are not
                        provided the memberships for the authenticating user are returned. -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; lists/memberships </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['lists_memberships'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!-- Returns the specified list. Private lists will only be shown if the authenticated user owns the specified list. -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; lists/show  </h3>
                        <pre>{!! $responses['lists_show'] !!}</pre>


                        <!--
                            Returns a timeline of tweets authored by members of the specified list. Retweets are included by default.
                            Use the include_rts=false parameter to omit retweets.
                        -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; lists/statuses </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['lists_statuses'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!--
                            Returns the locations that Twitter has trending topic information for.
                            The response is an array of “locations” that encode the location’s
                            WOEID and some other human-readable information such as a
                            canonical name and country the location belongs in.
                            A WOEID is a Yahoo! Where On Earth ID.
                        -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; trends/available </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['trends_available'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!--
                            Returns the locations that Twitter has trending topic information for,
                            closest to a specified location.
                        -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; trends/closest </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['trends_closest'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!--
                            Provides a simple, relevance-based search interface to public user accounts on Twitter.
                             Try querying by topical interest, full name, company name, location,
                             or other criteria. Exact match searches are not supported.
                        -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp;  users/search </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['users_search'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!--
                            Access to Twitter’s suggested user list. This returns the list of suggested user categories.
                            The category can be used in GET users / suggestions / :slug to get the users in that category.
                        -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp;  users/suggestions </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['users_suggestions'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!--
                            Access the users in a given category of the Twitter suggested user list and return their
                            most recent status if they are not a protected user.
                        -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp;users/suggestions/:slug/members</h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['users_suggestions_slug_members'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <!--
                            Returns a collection of the most recent Tweets posted by the user
                            indicated by the screen_name or user_id parameters.
                        -->
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; statuses/user_timeline </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['statuses_usertimeline'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
