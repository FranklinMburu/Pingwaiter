@extends('layouts.main')
@section('content')
<div class="container mx-auto p-4 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">Order Confirmation</h1>
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-lg font-semibold mb-2">Order #{{ $order->order_number }}</h2>
        <div class="mb-2">
            <span class="font-semibold">Customer:</span> {{ $order->customer->name ?? request('customer_name') }}<br>
            <span class="font-semibold">Phone:</span> {{ request('customer_phone') }}
        </div>
        <div class="mb-2">
            <span class="font-semibold">Table:</span> {{ $order->table->table_number ?? '-' }}
        </div>
        <div class="mb-2">
            <span class="font-semibold">Status:</span> {{ ucfirst($order->status) }}
        </div>
        <div class="overflow-x-auto block mb-4">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-2 py-1">Item</th>
                        <th class="px-2 py-1">Qty</th>
                        <th class="px-2 py-1">Price</th>
                        <th class="px-2 py-1">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td class="px-2 py-1">{{ $item->menuItem->name }}</td>
                        <td class="px-2 py-1">{{ $item->quantity }}</td>
                        <td class="px-2 py-1">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="px-2 py-1">{{ number_format($item->total_price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="bg-gray-50 p-4 rounded">
            <div class="flex justify-between py-1">
                <span>Subtotal:</span>
                <span>{{ number_format($order->subtotal, 2) }}</span>
            </div>
            <div class="flex justify-between py-1">
                <span>Tax:</span>
                <span>{{ number_format($order->tax_amount, 2) }}</span>
            </div>
            <div class="flex justify-between py-1 font-bold">
                <span>Total:</span>
                <span>{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>
        <div class="mt-4">
            <span class="font-semibold">Special Instructions:</span>
            <div class="text-gray-700">{{ $order->notes ?? 'None' }}</div>
        </div>
    </div>
</div>
@endsection
