@extends('layouts.main')
@section('title', 'Access Denied')
@section('content')
<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded shadow text-center">
    <h2 class="text-2xl font-bold text-red-600 mb-4">Access Denied</h2>
    @if(session('error'))
        <div class="mb-4 p-3 rounded bg-red-100 text-red-800 border border-red-300 text-sm font-semibold">
            <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
        </div>
    @else
        <p class="mb-4">You do not have permission to access this page or perform this action.</p>
    @endif
    <a href="{{ url()->previous() }}" class="btn btn-secondary">Go Back</a>
    <a href="{{ route('home') }}" class="btn btn-link">Return Home</a>
</div>
@endsection
