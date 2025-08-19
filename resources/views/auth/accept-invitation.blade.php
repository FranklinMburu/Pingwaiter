@extends('layouts.app')

@section('content')
<div class="container max-w-lg mx-auto py-5">
    <h2 class="mb-4 font-bold text-2xl">Accept Invitation</h2>

    @if(session('error'))
        <div class="alert alert-danger mb-3">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger mb-3">
            <strong>There were some problems with your submission:</strong>
            <ul class="mt-2 mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(isset($errorMessage))
        <div class="alert alert-danger mb-3">{{ $errorMessage }}</div>
    @endif

    <form method="GET" action="{{ route('google.login', ['invite_token' => $token]) }}">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" value="{{ $invite['email'] }}" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Role</label>
            <input type="text" class="form-control" value="{{ ucfirst($invite['role']) }}" disabled>
        </div>
        <button type="submit" class="btn btn-success w-100">Accept &amp; Continue with Google</button>
    </form>
</div>
@endsection
