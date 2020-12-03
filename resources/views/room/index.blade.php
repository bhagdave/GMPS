@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="card-header bg-primary">{{ $group->name ?? '' }}</div>
                <div class="card-body">
                    @foreach($roomEvents as $event)
                        @if ($event['type'] == 'm.room.message')
                            <p>{{$event['content']['body']}} <small>{{$event['sender']}}</small></p>
                            {{$event['origin_server_ts']}}
                            {{Carbon\Carbon::now()->getPreciseTimestamp(3)}}
                        @endif
                    @endforeach
                </div>
                <div class="card-footer">
                    <form method="post" action="/room/{{$group->id}}/sendMessage">
                        @csrf
                        <input type="text" placeholder="Type your message!" name="message">
                        <button>Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
