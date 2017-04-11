@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item"><a href="{{ url('/user/facebook') }}">Facebook Graph API</a></li>
                        <li class="list-group-item"><a href="{{ url('/user/instagram') }}">Instagram API</a></li>
                        <li class="list-group-item"><a href="{{ url('/user/twitter') }}">Twitter API</a></li>
                    </ul>
                </div>
                <?php echo menu('user', 'bootstrap') ?>
            </div>
        </div>
    </div>
</div>
@endsection
