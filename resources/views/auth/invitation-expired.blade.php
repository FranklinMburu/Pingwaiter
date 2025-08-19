@extends('layouts.app')

@section('content')
<div class="container text-center py-5">
    <h2 class="mb-4 text-danger">Invitation Link Expired or Invalid</h2>
    <p class="lead">Sorry, this invitation link is no longer valid. It may have expired or already been used.</p>
    <p>If you need a new invitation, please contact your administrator or the person who invited you.</p>
    <a href="{{ route('home') }}" class="btn btn-primary mt-4">Return to Home</a>
</div>
@endsection
