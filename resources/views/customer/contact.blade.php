@extends('layouts.customer')

@section('title', 'Contact Support - Pingwaiter')

@push('styles')
    <style>
        .contact-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 0 0 1px #e5e7eb;
        }
    </style>
@endpush

@section('content')
    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="contact-card text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Need Help?</h2>
            <p class="text-sm text-gray-600">Reach out to our support team and we’ll respond as soon as possible.</p>
        </div>

        <!-- Contact Form -->
        <div class="contact-card">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Send Us a Message</h3>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('customer.contact.submit') }}" method="POST" class="space-y-4">
                @csrf

                <textarea name="message" placeholder="Your Message" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 min-h-[120px] resize-none"></textarea>

                <div class="text-center">
                    <button type="submit"
                        class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-all">
                        Send Message
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection
