@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="mx-w-[475px] mx-auto text-center">
        <div class="w-full mx-auto mb-10">
            <a href="{{ route('home') }}"
                class="inline-flex items-center text-sm text-gray-600 hover:text-[#8065ee] transition font-medium">
                <i class="bi bi-arrow-left mr-2 text-base"></i>
                Back to Home
            </a>
        </div>

        <div class="p-5">
            <h2 class="text-4xl font-bold text-gray-900 font-playfair mb-2">Restaurant Sign In</h2>
            <p class="text-gray-600 text-lg mb-6">Sign in with your Google account to continue</p>

            <div class="text-center mb-4">
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

                <a href="{{ route('google.login', ['redirect' => route('dashboard'), 'role' => 'restaurant']) }}"
                    id="google-signin"
                    class="btn-google w-full flex items-center justify-center gap-3 bg-white border border-gray-300 rounded-lg py-3 px-4 text-gray-800 hover:bg-gray-50 transition shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48">
                        <path fill="#FFC107"
                            d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8c-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C12.955 4 4 12.955 4 24s8.955 20 20 20s20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z" />
                        <path fill="#FF3D00"
                            d="m6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C16.318 4 9.656 8.337 6.306 14.691z" />
                        <path fill="#4CAF50"
                            d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.91 11.91 0 0 1 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z" />
                        <path fill="#1976D2"
                            d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 0 1-4.087 5.571l.003-.002l6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z" />
                    </svg>
                    <span class="text-sm font-medium">Continue with Google</span>
                </a>
            </div>

            <div class="text-center mt-4">
                <span class="text-gray-600">Don't have an account?</span>
                <a href="{{ route('register') }}" class="text-[#8065ee] font-semibold ml-1 hover:underline">Create one</a>
            </div>
        </div>
    </div>
@endsection
