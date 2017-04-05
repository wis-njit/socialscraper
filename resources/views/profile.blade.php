@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Profile</div>

                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))

                                <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                            @endif
                        @endforeach
                    </div> <!-- end .flash-message -->

                    <div class="panel-body">
                        Link your other SNS accounts
                    </div>
                    @foreach($accounts as $account)


                        @if(session('currentProvider') === $account->name)
                            <a class="btn btn-default disabled" role="button" >{{ucfirst($account->name)}}</a>
                        @elseif($account->active === 1)
                            <a class="btn btn-default active" role="button" href="/user/disassociate/{{$account->name}}">Break {{ucfirst($account->name)}} Link</a>
                        @else
                            <a class="btn btn-primary active" role="button" href="/auth/{{$account->name}}nologin">Link {{ucfirst($account->name)}}</a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
