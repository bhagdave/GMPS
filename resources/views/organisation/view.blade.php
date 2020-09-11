@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-warning">{{ $organisation->name }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (isset($users))
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Nickname</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr scope="row">
                                        <td>{{ $user->name  }}</td>
                                        <td>{{ $user->nickname  }}</td>
                                        <td>{{ $user->email  }}</td>
                                        <td>
                                            @if ($user->main)
                                                <a class="deleteUser pr-2"  data-id="{{ $user->id}}" href="#">
                                                    <svg class="bi" width="24" height="24" fill="bg-warning">
                                                         <use xlink:href="/images/bootstrap-icons.svg#person-x-fill"/>
                                                    </svg>
                                                </a>
                                                <a href="/chat/{{ $user->id }}">
                                                    <svg class="bi" width="24" height="24" fill="bg-warning">
                                                         <use xlink:href="/images/bootstrap-icons.svg#chat-dots-fill"/>
                                                    </svg>
                                                </a>
                                            @else
                                                OWNER
                                            @endif
                                        </td>
                                    <tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="card-text">No users invited yet!</p>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="/organisation/invite/{{ $organisation->id }}">
                        <svg class="bi" width="24" height="24" fill="currentColor">
                             <use xlink:href="/images/bootstrap-icons.svg#person-plus-fill"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<form method="DELETE" action="/user/delete/" id="deleteForm" class="d-none">
    <input type="hidden" value="" id="deleteFormId">
</form>
@include('partials.confirm-modal')
@endsection
@section('scripts')
<script>
    $(document).ready(function(){
        $('.deleteUser').click(function(e){
            var $deleteLink = $(this);

            $('#confirm').modal({ backdrop: 'static', keyboard: false})
                .on('click', '#delete-btn', function(){
                    userId = $($deleteLink).data('id');
                    $('#deleteFormId').val(userId);
                    $('#deleteForm').submit();
                });
        });
    });
</script>
@endsection
