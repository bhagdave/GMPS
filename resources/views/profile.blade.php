@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <form method="post">
                    @csrf
                    <div class="card-header bg-secondary">{{ $user->name }} {{ __('Profile') }} </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <ul id="errors">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="nickName">Nickname</label>
                            <input type="text" name="nickname" value='{{ $user->nickname }}'  class="form-control" id="nickName" aria-describedby="nicknameHelp" placeholder="Enter nickname">
                            <small id="nicknameHelp" class="form-text text-muted">Your nickname</small>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" name="email" value='{{  $user->email }}' required class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email">
                            <small id="emailHelp" class="form-text text-muted">Changing your email will affect your login.</small>
                        </div>

                    </div>
                    <div class="card-footer">
                        <div class="btn-group" role="group">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" onclick="goBack()" class="btn btn-danger">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
function goBack() {
  window.history.back();
}
</script>
