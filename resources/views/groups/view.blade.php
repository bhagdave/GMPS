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
                    <p class="card-text">No participants invited yet!</p>
                </div>
                <div class="card-footer">
                    <a href="/group/invite">
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
