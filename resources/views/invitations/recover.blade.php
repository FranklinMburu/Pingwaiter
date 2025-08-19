@extends('layouts.main')
@section('title', 'Recover Invitation')
@section('content')
<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded shadow text-center">
    <h2 class="text-2xl font-bold text-primary-700 mb-4">Recover Invitation</h2>
    <p class="mb-4 text-gray-600">If you lost your invitation email, enter your invitation token below to accept your invite.</p>
    <form method="POST" action="{{ route('invitations.recover.submit') }}" class="space-y-4">
        @csrf
        <input type="text" name="token" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-400" placeholder="Invitation Token" required autofocus>
        @if ($errors->has('token'))
            <div class="text-red-600 text-sm">{{ $errors->first('token') }}</div>
        @endif
        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2 rounded transition">Accept Invitation</button>
    </form>
    <div class="mt-4">
        <a href="{{ route('home') }}" class="text-primary-600 hover:underline">Return Home</a>
    </div>
</div>
@endsection
