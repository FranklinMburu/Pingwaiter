@extends('layouts.main')
@section('content')
<div class="container mx-auto p-4 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">Checkout</h1>
    @if(session('error'))
        <div class="mb-4 p-3 bg-red-500 text-white rounded shadow animate-pulse">{{ session('error') }}</div>
    @endif
    <form method="POST" action="{{ route('orders.store') }}" class="space-y-4 bg-white p-6 rounded shadow">
        @csrf
        <div>
            <h2 class="text-lg font-semibold mb-2">Your Cart</h2>
            <div class="overflow-x-auto block">
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
                        @foreach($menuItems as $item)
                        <tr>
                            <td class="px-2 py-1">{{ $item->name }}</td>
                            <td class="px-2 py-1">
                                <input type="number" name="cart[{{ $item->id }}]" value="{{ $cart[$item->id] }}" min="1" class="input input-bordered w-16 text-center" required>
                                @error('cart.' . $item->id)<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                            </td>
                            <td class="px-2 py-1">{{ number_format($item->price, 2) }}</td>
                            <td class="px-2 py-1">{{ number_format($item->price * $cart[$item->id], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-2 flex justify-between">
                <a href="{{ route('menu.index') }}" class="text-blue-600 underline">Back to Menu</a>
                <span class="text-xs text-gray-500">Table: <span class="font-semibold">{{ $tableId ?? 'Not assigned' }}</span></span>
            </div>
        </div>
        <div class="bg-gray-50 p-4 rounded">
            <h2 class="text-lg font-semibold mb-2">Order Summary</h2>
            @php
                $subtotal = 0;
                foreach($menuItems as $item) {
                    $subtotal += $item->price * $cart[$item->id];
                }
                $taxRate = config('app.tax_rate', 0.10);
                $taxAmount = round($subtotal * $taxRate, 2);
                $totalAmount = round($subtotal + $taxAmount, 2);
            @endphp
            <div class="flex justify-between py-1">
                <span>Subtotal:</span>
                <span>{{ number_format($subtotal, 2) }}</span>
            </div>
            <div class="flex justify-between py-1">
                <span>Tax ({{ $taxRate * 100 }}%):</span>
                <span>{{ number_format($taxAmount, 2) }}</span>
            </div>
            <div class="flex justify-between py-1 font-bold">
                <span>Total:</span>
                <span>{{ number_format($totalAmount, 2) }}</span>
            </div>
        </div>
        <div>
            <h2 class="text-lg font-semibold mb-2">Customer Information</h2>
            <input type="text" name="customer_name" class="input input-bordered w-full mb-2" placeholder="Name" required>
            @error('customer_name')<div class="text-red-500 text-xs mb-2">{{ $message }}</div>@enderror
            <input type="text" name="customer_phone" class="input input-bordered w-full mb-2" placeholder="Phone" required>
            @error('customer_phone')<div class="text-red-500 text-xs mb-2">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="block font-semibold mb-1">Special Instructions</label>
            <textarea name="notes" class="textarea textarea-bordered w-full" placeholder="Any special requests?"></textarea>
            @error('notes')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="flex justify-end items-center gap-2">
            <button type="submit" class="btn btn-primary min-w-[44px] min-h-[44px] flex items-center gap-2">
                <span id="order-btn-text">Place Order</span>
                <span id="order-btn-loading" class="hidden animate-spin w-5 h-5 border-2 border-t-2 border-white rounded-full"></span>
            </button>
        </div>
    </form>
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            document.getElementById('order-btn-text').textContent = 'Placing...';
            document.getElementById('order-btn-loading').classList.remove('hidden');
        });
    </script>
</div>
@endsection
