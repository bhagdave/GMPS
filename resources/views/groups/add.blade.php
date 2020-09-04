@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <form method="post">
                    @csrf
                    <div class="card-header bg-primary">{{ __('Add Group') }} </div>

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
                            <label for="groupName">Group Name</label>
                            <input type="text" name="name" required class="form-control" id="groupName" aria-describedby="nameHelp" placeholder="Enter group name">
                            <small id="nameHelp" class="form-text text-muted">The name of the group to be created</small>
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
<script>
function goBack() {
   window.history.back();
}
</script>
@endsection
