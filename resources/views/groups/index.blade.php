@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">{{ $user->name }} {{ __('Groups') }} </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (count($user->groups) > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($user->groups as $group)
                                <li class="list-group-item">{{ $group->name }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="card-text">No groups created yet!</p>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="/group/add">
                        <svg class="bi" width="24" height="24" fill="currentColor">
                             <use xlink:href="images/bootstrap-icons.svg#plus-square-fill"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
