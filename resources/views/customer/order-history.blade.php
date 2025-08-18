@extends('layouts.customer')

@section('title', 'Order History')

@push('styles')
@endpush

@section('content')
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Order History -->
        <div class="content-card">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Your Orders</h2>

            @forelse ($orders as $order)
                <div class="order-card mb-6 p-4 border border-gray-200 rounded-xl bg-white shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-semibold text-gray-900">Order #{{ $order->id }}</h3>
                            <p class="text-sm text-gray-500">{{ $order->created_at->format('F j, Y \a\t h:i A') }}</p>
                        </div>
                        <div class="text-right">
                            <span
                                class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                            @switch($order->status->value)
                                @case('Editable') bg-gray-200 text-gray-700 @break
                                @case('Pending') bg-yellow-100 text-yellow-800 @break
                                @case('Approved') bg-blue-100 text-blue-800 @break
                                @case('Prepared') bg-indigo-100 text-indigo-800 @break
                                @case('Delivered') bg-purple-100 text-purple-800 @break
                                @case('Completed') bg-green-100 text-green-700 @break
                                @default bg-gray-100 text-gray-600
                            @endswitch">
                                {{ $order->status->label() }}
                            </span>
                            <div class="text-lg font-bold text-gray-900 mt-1">
                                {{ $restaurant['currency_symbol'] }}{{ number_format($order->price, 2) }}
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2 mb-4">
                        <div class="flex items-start gap-4">
                            <img src="/uploads/food/pictures/{{ $order->item->picture[0] ?? 'placeholder.png' }}"
                                alt="Food Image" class="w-16 h-16 object-cover rounded-lg shadow">

                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $order->item->name }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ $restaurant['currency_symbol'] }}{{ number_format($order->item->price, 2) }} ×
                                    {{ $order->quantity }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="flex justify-end mt-4">
                        <form action="{{ route('orders.repeat', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center py-4 px-8 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 transition">
                                <i class="bi bi-arrow-repeat mr-2"></i> Repeat Order
                            </button>
                        </form>
                    </div> --}}
                </div>
            @empty
                <div class="bg-gray-50 text-center py-10 rounded-lg">
                    <i class="bi bi-receipt text-4xl text-gray-300 mb-3"></i>
                    <h4 class="text-gray-600 font-medium">No orders yet</h4>
                    <p class="text-sm text-gray-500">Start by placing an order from the menu.</p>
                </div>
            @endforelse
        </div>
    </main>
@endsection

@push('scripts')
@endpush
