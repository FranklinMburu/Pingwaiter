@extends('layouts.main')
@section('title', 'Welcome Admin!')
@section('content')
<div class="max-w-lg mx-auto mt-10 p-8 bg-white rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Welcome, Admin!</h1>
    <p class="mb-4">You are the first administrator of this workspace. You now have full access to all admin features, including inviting staff and managing the system.</p>
    <ul class="list-disc ml-6 mb-4">
        <li>Invite staff or workers from the <a href="{{ route('invitations.index') }}" class="text-blue-600 underline">Invitations</a> page.</li>
        <li>Access admin features from the sidebar menu.</li>
        <li>Contact support if you need help with onboarding.</li>
    </ul>
    <a href="{{ route('dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
</div>
@endsection
