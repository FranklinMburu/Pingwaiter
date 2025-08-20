@extends('layouts.customer')

@section('title', 'Order History')

@push('styles')
    <style>
        .content-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .status-step {
            display: flex;
            align-items: center;
            margin-bottom: 24px;
            position: relative;
        }

        .status-step:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 20px;
            top: 48px;
            width: 2px;
            height: 40px;
            background: #e2e8f0;
        }

        .status-step.completed::after {
            background: #10b981;
        }

        .status-step.active::after {
            background: #8065ee;
        }

        .status-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 16px;
            flex-shrink: 0;
        }

        .status-icon.completed {
            background: #10b981;
            color: white;
        }

        .status-icon.active {
            background: #8065ee;
            color: white;
            animation: pulse 2s infinite;
        }

        .status-icon.pending {
            background: #e2e8f0;
            color: #64748b;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        .order-item {
            background: #f8fafc;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
        }

        @media (max-width: 768px) {
            .content-card {
                padding: 20px;
                margin-bottom: 20px;
                border-radius: 16px;
            }
        }
    </style>
@endpush

@section('content')
    @if(isset($customer) && $customer->isBanned())
        <x-ban-notice :banReason="$customer->ban_reason" :contact="config('app.contact_email', 'support@example.com')" />
        <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center text-red-700 font-semibold mb-6">
            You cannot view or manage orders while banned.
        </div>
    @else
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Current Order -->
            <div class="content-card mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Current Order</h2>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Order #{{ $order->id }}</div>
                        <div class="text-sm text-gray-500">Placed at {{ $order->created_at->format('g:i A') }}</div>
                    </div>
                </div>

            <!-- Estimated Time -->
            <div class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-xl p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-900">Estimated Time</h3>
                        <p class="text-sm text-gray-600">Your order will be ready soon</p>
                    </div>
                    <div class="text-right">
                        {{-- <div class="text-2xl font-bold text-purple-600" id="countdown">12:45</div>
                        <div class="text-sm text-gray-500">minutes</div> --}}
                    </div>
                </div>
            </div>

            <!-- Order Progress -->
            <div class="mb-6">
                <h3 class="font-semibold text-gray-900 mb-4">Order Progress</h3>

                @php
                    $statuses = [
                        'Editable' => 'Received',
                        'Pending' => 'Preparing',
                        'Approved' => 'Being Prepared',
                        'Prepared' => 'Ready for Pickup',
                        'Delivered' => 'Served',
                        'Completed' => 'Paid & Completed',
                    ];

                    $statusKeys = array_keys($statuses);
                    $currentIndex = array_search($order->status->value, $statusKeys);
                @endphp

                @foreach ($statuses as $key => $label)
                    @php
                        $stepIndex = $loop->index;
                        $isCompleted =
                            $stepIndex < $currentIndex ||
                            ($key === 'Completed' && $order->status->value === 'Completed');
                        $isCurrent = $stepIndex === $currentIndex && !$isCompleted;
                    @endphp

                    <div class="status-step {{ $isCompleted ? 'completed' : ($isCurrent ? 'active' : '') }}">
                        <div class="status-icon {{ $isCompleted ? 'completed' : ($isCurrent ? 'active' : 'pending') }}">
                            <i
                                class="bi {{ $isCompleted ? 'bi-check-circle-fill' : ($isCurrent ? 'bi-arrow-repeat' : 'bi-clock') }}"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $label }}</h4>
                            <p class="text-sm text-gray-500">
                                @switch($key)
                                    @case('Editable')
                                        Your order has been confirmed
                                    @break

                                    @case('Pending')
                                        Your order is waiting to be prepared
                                    @break

                                    @case('Approved')
                                        Your order is being prepared
                                    @break

                                    @case('Prepared')
                                        We'll notify you when ready
                                    @break

                                    @case('Delivered')
                                        Enjoy your meal!
                                    @break

                                    @case('Completed')
                                        Your order has been fulfilled. Thank you for dining with us!
                                    @break
                                @endswitch
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ $stepIndex <= $currentIndex ? $order->created_at->addMinutes($stepIndex * 5)->format('g:i A') : '...' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Order Items -->
            <div>
                <h3 class="font-semibold text-gray-900 mb-4">Order Items</h3>

                <div class="order-item mb-4">
                    <img src="{{ asset('uploads/food/pictures/' . ($order->item->picture[0] ?? 'placeholder.png')) }}"
                        alt="{{ $order->item->name }}" class="w-20 h-20 object-cover rounded-lg shadow border">

                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $order->item->name }}</h4>
                            <p class="text-sm text-gray-600">{{ $order->item->description ?? '' }}</p>
                        </div>
                        <div class="text-right">
                            <div class="font-medium text-gray-900">
                                {{ $restaurant['currency_symbol'] }}{{ number_format($order->item->price, 2) }}</div>
                            <div class="text-sm text-gray-500">Qty: {{ $order->quantity }}</div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4 mt-4">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-gray-900">Total</span>
                        <span
                            class="font-bold text-lg text-gray-900">{{ $restaurant['currency_symbol'] }}{{ number_format($order->price, 2) }}</span>
                    </div>
                </div>


                <div class="border-t border-gray-200 pt-4 mt-4">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-gray-900">Total</span>
                        <span
                            class="font-bold text-lg text-gray-900">{{ $restaurant['currency_symbol'] }}{{ number_format($order->price, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="content-card">
            <h3 class="font-semibold text-gray-900 mb-4">Need Help?</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <button onclick="callWaiter()"
                    class="flex items-center justify-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl hover:from-purple-100 hover:to-purple-200 transition-all">
                    <i class="bi bi-person-raised-hand mr-2 text-xl"></i>
                    Call Waiter
                </button>

                <form action="{{ route('orders.repeat', ['table' => $tableCode, 'order' => $order->id]) }}" method="POST"
                    class="">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center px-4 py-4 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="bi bi-arrow-repeat mr-2"></i> Repeat This Order
                    </button>
                </form>


                {{-- <form method="POST" action="{{ route('order.cancel', ['order' => $order->id]) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="flex items-center justify-center p-4 bg-gradient-to-r from-red-50 to-red-100 rounded-xl hover:from-red-100 hover:to-red-200 transition-all w-full">
                        <i class="bi bi-x-lg mr-2 text-xl"></i>
                        Cancel Order
                    </button>
                </form> --}}
            </div>
        </div>
    </main>

@endsection

@push('scripts')
    @vite(['resources/js/echo.js'])

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const orderId = "{{ $order->id }}";

            if (typeof Echo === "undefined") {
                console.warn("Echo is not available. Retrying...");
                setTimeout(arguments.callee, 100);
                return;
            }

            Echo.private(`order.${orderId}`)
                .listen('.OrderStatusUpdated', (data) => {
                    console.log('📦 Order status updated:', data);
                    if (data.status) {
                        location.reload();
                    }
                });

            Echo.connector.pusher.connection.bind('connected', () => {
                console.log('%c✅ Pusher connected to Order channel!', 'color: green; font-weight: bold;');
            });

            Echo.connector.pusher.connection.bind('error', (err) => {
                console.error('❌ Pusher connection error (order):', err);
            });
        });
    </script>
@endpush
