
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
                    <div class="panel-heading text-center">Twitter</div>
                    <div class="panel-body">
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
