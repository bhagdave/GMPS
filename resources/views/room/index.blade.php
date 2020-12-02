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
                    {{var_dump($messages['chunk'])}}
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
