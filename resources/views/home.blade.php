@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-secondary">{{ $user->organisation->name ?? '' }} {{ __('Dashboard') }} </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
                <div class="row d-flex justify-content-between">
                    <div class="pb-4 pl-4 ml-4 col-md-5">
                        <div class="card">
                            <div class="card-header">Joined Rooms</div>
                                <div class="card-body">
                                    @foreach ($joinedRooms['groups'] as $room)
                                        <p><a href="/room/{{$room->id}}"> {{$room->name}}</a>
                                            <small>Unread Messages:{{$joinedRooms['rooms'][$room->matrix_room_id]['unread_notifications']['notification_count'] ?? 0}}</small>
                                        </p>
                                    @endforeach
                                </div>
                        </div>
                    </div>
                    <div class="pb-4 pr-4 mr-4 col-md-5">
                        <div class="card">
                            <div class="card-header">Invited Rooms</div>
                                @foreach ($invitedRooms as $room)
                                    <p>{{$room->name}}</p>
                                @endforeach
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
