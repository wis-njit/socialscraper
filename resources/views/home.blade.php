@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <ul class="list-group">
                        @foreach($accounts as $account)

                            @if($account->name !== 'google')
                                @if($account->active === 1)
                                    <li class="list-group-item"><a href="{{ url('/user/' . $account->name) }}">{{ucfirst($account->name)}} API</a></li>
                                @else
                                    <li class="list-group-item">{{ucfirst($account->name)}} API <a class="pull-right" href="{{url('/user/profile')}}">Not available</a></li>
                                @endif

                            @endif
                        @endforeach
                    </ul>
                </div>
                <?php echo menu('user', 'bootstrap') ?>
            </div>
        </div>
    </div>
</div>
@endsection
