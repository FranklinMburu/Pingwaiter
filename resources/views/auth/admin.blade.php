@extends('layouts.auth')

@section('title', 'Worker Login')

@section('content')
    <div class="max-w-[475px] mx-auto">
        <div class="p-5">
            <div class="text-center mb-8">
                <h2 class="text-4xl font-bold text-gray-900 font-playfair mb-2">Worker Sign In</h2>
                <p class="text-gray-600 text-lg mb-6">Sign in to access the worker panel</p>
            </div>

            <!-- Error Messages -->
            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <strong>There were some errors with your submission:</strong>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('worker.login.store') }}" method="POST" class="mb-6">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                        required>
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
                    <input type="password" name="password" id="password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                        required>
                </div>
                <button type="submit"
                    class="w-full bg-teal-600 text-white py-3 rounded-lg hover:bg-teal-700 transition font-medium">
                    Sign In
                </button>
            </form>

            <div class="text-center mt-4">
                <span class="text-gray-600">Don't have an account?</span>
                <a href="{{ route('register') }}" class="text-teal-600 font-semibold ml-1 hover:underline">Create one</a>
            </div>
        </div>
    </div>
@endsection
