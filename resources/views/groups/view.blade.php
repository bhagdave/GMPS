@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">{{ $group->name }} {{ __('Group') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (isset($group->participants))
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Joined</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($group->participants as $participant)
                                    <tr scope="row">
                                        <td>{{ $participant->name  }}</td>
                                        <td>{{ $participant->pivot->type  }}</td>
                                        <td>{{ $participant->pivot->created_at  }}</td>
                                        <td>
                                            @if ($participant->pivot->type != 'owner')
                                                <a class="pr-2" href="/participant/remove/{{ $group->id }}/{{ $participant->id }}">
                                                    <svg class="bi" width="24" height="24" fill="currentColor">
                                                         <use xlink:href="/images/bootstrap-icons.svg#person-x-fill"/>
                                                    </svg>
                                                </a>
                                                <a href="/chat/{{ $participant->id }}">
                                                    <svg class="bi" width="24" height="24" fill="currentColor">
                                                         <use xlink:href="/images/bootstrap-icons.svg#chat-dots-fill"/>
                                                    </svg>
                                                </a>
                                            @endif
                                        </td>
                                    <tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="card-text">No participants invited yet!</p>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="/group/invite/{{ $group->id }}">
                        <svg class="bi" width="24" height="24" fill="currentColor">
                             <use xlink:href="/images/bootstrap-icons.svg#person-plus-fill"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
