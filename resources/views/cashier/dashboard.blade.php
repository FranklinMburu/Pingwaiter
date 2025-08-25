@extends('layouts.app')
@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-xl font-bold mb-4">Cashier Dashboard</h1>
    <div>
        <h2 class="text-lg font-semibold mb-2">Delivered Orders Needing Payment</h2>
        <ul id="delivered-orders" aria-label="Delivered Orders">
            @forelse($deliveredOrders as $order)
            <li class="mb-2 p-2 bg-white rounded shadow flex flex-col md:flex-row justify-between items-center" tabindex="0">
                <div class="flex flex-col md:flex-row items-center gap-2">
                    <span class="font-bold">Order #{{ $order->id }}</span>
                    <span class="badge bg-purple-200 text-purple-800 px-2 py-1 rounded">Delivered</span>
                    <span class="text-sm">Table: {{ $order->table->number ?? '-' }}</span>
                    <span class="text-sm">Items: {{ $order->items->pluck('menuItem.name')->join(', ') }}</span>
                    <span class="text-sm">Total: ${{ number_format($order->total_amount, 2) }}</span>
                    <span class="text-sm">Customer: {{ $order->customer->name ?? 'N/A' }} ({{ $order->customer->email ?? 'N/A' }})</span>
                </div>
                <form method="POST" action="{{ route('cashier.pay', $order) }}" onsubmit="return confirm('Process payment for this order?')">
                    @csrf
                    <button class="btn btn-success px-3 py-1 rounded" type="submit" aria-label="Process payment for order #{{ $order->id }}" @if(!$order->canTransitionTo('paid')) disabled @endif>Process Payment</button>
                </form>
            </li>
            @empty
            <li class="text-gray-500 p-4">No delivered orders to show.</li>
            @endforelse
        </ul>
    </div>
</div>
<script>
    setInterval(function() {
        fetch("{{ route('cashier.dashboard.data') }}")
            .then(response => response.json())
            .then(data => {
                let deliveredHtml = '';
                if(data.deliveredOrders.length === 0) {
                    deliveredHtml = '<li class="text-gray-500 p-4">No delivered orders to show.</li>';
                } else {
                    data.deliveredOrders.forEach(order => {
                        deliveredHtml += `<li class='mb-2 p-2 bg-white rounded shadow flex flex-col md:flex-row justify-between items-center' tabindex='0'><div class='flex flex-col md:flex-row items-center gap-2'><span class='font-bold'>Order #${order.id}</span><span class='badge bg-purple-200 text-purple-800 px-2 py-1 rounded'>Delivered</span><span class='text-sm'>Table: ${order.table?.number ?? '-'}</span><span class='text-sm'>Items: ${order.items.map(i => i.menuItem.name).join(', ')}</span><span class='text-sm'>Total: $${order.total_amount.toFixed(2)}</span><span class='text-sm'>Customer: ${order.customer?.name ?? 'N/A'} (${order.customer?.email ?? 'N/A'})</span></div><form method='POST' action='/cashier/pay/${order.id}' onsubmit='return confirm("Process payment for this order?")'>@csrf<button class='btn btn-success px-3 py-1 rounded' type='submit' aria-label='Process payment for order #${order.id}' ${!order.canTransitionToPaid ? 'disabled' : ''}>Process Payment</button></form></li>`;
                    });
                }
                document.getElementById('delivered-orders').innerHTML = deliveredHtml;
            });
    }, 5000);
</script>
@endsection
