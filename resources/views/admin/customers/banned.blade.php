@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto mt-10 p-6 bg-white rounded-xl shadow-lg">
    <h2 class="text-2xl font-bold mb-6 text-center">Customer Ban Management</h2>
    @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 rounded bg-red-100 text-red-700 text-sm">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-4 p-3 rounded bg-red-100 text-red-700 text-sm">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div x-data="{ search: '', showBan: false }">
        <!-- Search Form -->
        <form method="GET" class="mb-6 flex flex-col md:flex-row gap-4 items-center">
            <input type="email" name="search" x-model="search" placeholder="Search by email" class="form-input w-full md:w-1/2 px-4 py-2 rounded border border-gray-300 focus:ring focus:ring-primary-500" />
            <button type="submit" class="px-6 py-2 rounded bg-primary-500 text-white font-semibold hover:bg-primary-600 transition">Search</button>
        </form>
        <!-- Ban Form -->
        <button type="button" @click="showBan = !showBan" class="mb-4 px-6 py-2 rounded bg-red-500 text-white font-semibold hover:bg-red-600 transition">Ban a Customer</button>
        <form x-show="showBan" method="POST" action="{{ route('customer.banByEmail') }}" class="mb-8 p-4 bg-red-50 rounded-lg border border-red-200" @submit.prevent="$el.submit()">
            @csrf
            <div class="mb-4">
                <label for="email" class="block font-semibold mb-1">Customer Email</label>
                <input type="email" name="email" id="email" required class="form-input w-full px-4 py-2 rounded border border-gray-300 focus:ring focus:ring-primary-500" />
            </div>
            <div class="mb-4">
                <label for="reason" class="block font-semibold mb-1">Ban Reason</label>
                <textarea name="reason" id="reason" required rows="3" class="form-textarea w-full px-4 py-2 rounded border border-gray-300 focus:ring focus:ring-primary-500"></textarea>
            </div>
            <button type="submit" class="px-6 py-2 rounded bg-red-500 text-white font-semibold hover:bg-red-600 transition">Ban Customer</button>
        </form>
        <!-- Banned Customers List -->
        <h3 class="text-lg font-bold mb-4">Currently Banned Customers</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-lg shadow">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Ban Reason</th>
                        <th class="px-4 py-2 text-left">Banned At</th>
                        <th class="px-4 py-2 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banned as $customer)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $customer->user->email }}</td>
                            <td class="px-4 py-2">{{ $customer->ban_reason }}</td>
                            <td class="px-4 py-2">{{ $customer->banned_at ? $customer->banned_at->format('Y-m-d H:i') : '' }}</td>
                            <td class="px-4 py-2">
                                <form method="POST" action="{{ route('customer.unbanByEmail') }}" @submit.prevent="if(confirm('Unban this customer?')) $el.submit()">
                                    @csrf
                                    <input type="hidden" name="email" value="{{ $customer->user->email }}">
                                    <button type="submit" class="px-4 py-1 rounded bg-green-500 text-white font-semibold hover:bg-green-600 transition">Unban</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-2 text-center text-gray-500">No banned customers found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
