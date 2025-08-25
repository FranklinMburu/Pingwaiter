@extends('layouts.app')
@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-xl font-bold mb-4">Cook Dashboard</h1>
    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <h2 class="text-lg font-semibold mb-2">Confirmed Orders to Prepare</h2>
            <ul id="confirmed-orders" aria-label="Confirmed Orders">
                @forelse($confirmedOrders as $order)
                <li class="mb-2 p-2 bg-white rounded shadow flex flex-col md:flex-row justify-between items-center" tabindex="0">
                    <div class="flex flex-col md:flex-row items-center gap-2">
                        <span class="font-bold">Order #{{ $order->id }}</span>
                        <span class="badge bg-blue-200 text-blue-800 px-2 py-1 rounded">Confirmed</span>
                        <span class="text-sm">Table: {{ $order->table->number ?? '-' }}</span>
                        <span class="text-sm">Items: {{ $order->items->pluck('menuItem.name')->join(', ') }}</span>
                        <span class="text-sm">Total: ${{ number_format($order->total_amount, 2) }}</span>
                        <span class="text-sm">Customer: {{ $order->customer->name ?? 'N/A' }} ({{ $order->customer->email ?? 'N/A' }})</span>
                    </div>
                    <form method="POST" action="{{ route('cook.preparing', $order) }}" onsubmit="return confirm('Start preparing this order?')">
                        @csrf
                        <button class="btn btn-warning px-3 py-1 rounded" type="submit" aria-label="Start preparing order #{{ $order->id }}" @if(!$order->canTransitionTo('preparing')) disabled @endif>Start Preparing</button>
                    </form>
                </li>
                @empty
                <li class="text-gray-500 p-4">No confirmed orders to show.</li>
                @endforelse
            </ul>
        </div>
        <div>
            <h2 class="text-lg font-semibold mb-2">Preparing Orders</h2>
            <ul id="preparing-orders" aria-label="Preparing Orders">
                @forelse($preparingOrders as $order)
                <li class="mb-2 p-2 bg-white rounded shadow flex flex-col md:flex-row justify-between items-center" tabindex="0">
                    <div class="flex flex-col md:flex-row items-center gap-2">
                        <span class="font-bold">Order #{{ $order->id }}</span>
                        <span class="badge bg-yellow-200 text-yellow-800 px-2 py-1 rounded">Preparing</span>
                        <span class="text-sm">Table: {{ $order->table->number ?? '-' }}</span>
                        <span class="text-sm">Items: {{ $order->items->pluck('menuItem.name')->join(', ') }}</span>
                        <span class="text-sm">Total: ${{ number_format($order->total_amount, 2) }}</span>
                        <span class="text-sm">Customer: {{ $order->customer->name ?? 'N/A' }} ({{ $order->customer->email ?? 'N/A' }})</span>
                    </div>
                    <form method="POST" action="{{ route('cook.ready', $order) }}" onsubmit="return confirm('Mark this order as ready?')">
                        @csrf
                        <button class="btn btn-success px-3 py-1 rounded" type="submit" aria-label="Mark order #{{ $order->id }} as ready" @if(!$order->canTransitionTo('ready')) disabled @endif>Mark Ready</button>
                    </form>
                </li>
                @empty
                <li class="text-gray-500 p-4">No preparing orders to show.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
<script>
    setInterval(function() {
        fetch("{{ route('cook.dashboard.data') }}")
            .then(response => response.json())
            .then(data => {
                let confirmedHtml = '';
                if(data.confirmedOrders.length === 0) {
                    confirmedHtml = '<li class="text-gray-500 p-4">No confirmed orders to show.</li>';
                } else {
                    data.confirmedOrders.forEach(order => {
                        confirmedHtml += `<li class='mb-2 p-2 bg-white rounded shadow flex flex-col md:flex-row justify-between items-center' tabindex='0'><div class='flex flex-col md:flex-row items-center gap-2'><span class='font-bold'>Order #${order.id}</span><span class='badge bg-blue-200 text-blue-800 px-2 py-1 rounded'>Confirmed</span><span class='text-sm'>Table: ${order.table?.number ?? '-'}</span><span class='text-sm'>Items: ${order.items.map(i => i.menuItem.name).join(', ')}</span><span class='text-sm'>Total: $${order.total_amount.toFixed(2)}</span><span class='text-sm'>Customer: ${order.customer?.name ?? 'N/A'} (${order.customer?.email ?? 'N/A'})</span></div><form method='POST' action='/cook/preparing/${order.id}' onsubmit='return confirm("Start preparing this order?")'>@csrf<button class='btn btn-warning px-3 py-1 rounded' type='submit' aria-label='Start preparing order #${order.id}' ${!order.canTransitionToPreparing ? 'disabled' : ''}>Start Preparing</button></form></li>`;
                    });
                }
                document.getElementById('confirmed-orders').innerHTML = confirmedHtml;
                let preparingHtml = '';
                if(data.preparingOrders.length === 0) {
                    preparingHtml = '<li class="text-gray-500 p-4">No preparing orders to show.</li>';
                } else {
                    data.preparingOrders.forEach(order => {
                        preparingHtml += `<li class='mb-2 p-2 bg-white rounded shadow flex flex-col md:flex-row justify-between items-center' tabindex='0'><div class='flex flex-col md:flex-row items-center gap-2'><span class='font-bold'>Order #${order.id}</span><span class='badge bg-yellow-200 text-yellow-800 px-2 py-1 rounded'>Preparing</span><span class='text-sm'>Table: ${order.table?.number ?? '-'}</span><span class='text-sm'>Items: ${order.items.map(i => i.menuItem.name).join(', ')}</span><span class='text-sm'>Total: $${order.total_amount.toFixed(2)}</span><span class='text-sm'>Customer: ${order.customer?.name ?? 'N/A'} (${order.customer?.email ?? 'N/A'})</span></div><form method='POST' action='/cook/ready/${order.id}' onsubmit='return confirm("Mark this order as ready?")'>@csrf<button class='btn btn-success px-3 py-1 rounded' type='submit' aria-label='Mark order #${order.id} as ready' ${!order.canTransitionToReady ? 'disabled' : ''}>Mark Ready</button></form></li>`;
                    });
                }
                document.getElementById('preparing-orders').innerHTML = preparingHtml;
            });
    }, 5000);
</script>
@endsection
