@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <form action="/participant/invite/{{ $group->id }}" method="post">
                    @csrf
                    <div class="card-header bg-primary">{{ __('Invite Particpant') }} to {{ $group->name }} </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
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
                            <label for="email">Email</label>
                            <input type="email" name="email" required class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email address for invite">
                            <small id="emailHelp" class="form-text text-muted">The invite will be sent to this address</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="btn-group" role="group">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="/group/{{ $group->id }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
function goBack() {
   window.history.back();
}
</script>
@endsection
