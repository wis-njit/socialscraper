@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Profile</div>

                    <div class="panel-body">
                        Link your other SNS accounts
                    </div>
                    <a class="btn btn-link" href="/auth/googlenologin">Link Google</a>
                    <a class="btn btn-link" href="/auth/facebooknologin">Link Facebook </a>
                    <a class="btn btn-link" href="/auth/twitternologin">Link Twitter</a>
                    <a class="btn btn-link" href="/auth/instagramnologin">Link Instagram</a>
                </div>
            </div>
        </div>
    </div>
@endsection
