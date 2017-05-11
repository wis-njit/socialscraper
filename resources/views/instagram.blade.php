

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
                            @foreach($accounts as $account)

                                @if($account->name !== 'google')
                                    @if($account->active === 1)
                                        <a class="col-md-4" href="{{ url('/user/' . $account->name) }}">{{ucfirst($account->name)}} API</a>
                                    @else
                                        <a href="{{url('/user/profile')}}" class="col-md-4"> {{ucfirst($account->name)}} API Not available </a>
                                    @endif

                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="panel-heading text-center">Instagram</div>
                    <div class="panel-body">
                        <h3> <span class="post">&nbsp;POST&nbsp;</span>&nbsp;/media/{media-id}/likes </h3>
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
                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /users/self/follows </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['users_self_follows'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /users/self/followed-by </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['users_self_followedby'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /users/self/requested-by </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['users_self_requestedby'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /users/user-id/relationship </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['users_userid_relationship'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /users/self </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['users_self'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /users/user-id </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['users_userid'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; /media/media-id </h3>
                        @include('dummy')

                        <h3><span class="get">&nbsp;GET&nbsp;</span>&nbsp; user/self/media/liked </h3>
                        <script src="/js/prettify.js">

                        </script>
                        <script>
                            var test = {!! $responses['users_self_media_liked'] !!};
                            var jsonPretty = JSON.stringify(test, undefined, 4);
                            output(syntaxHighlight(jsonPretty));
                        </script>

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
