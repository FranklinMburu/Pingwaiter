@extends('layouts.app')
@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-xl font-bold mb-4">Waiter Dashboard</h1>
    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <h2 class="text-lg font-semibold mb-2">Pending Orders</h2>
            <ul id="pending-orders" aria-label="Pending Orders">
                @forelse($pendingOrders as $order)
                <li class="mb-2 p-2 bg-white rounded shadow flex flex-col md:flex-row justify-between items-center" tabindex="0">
                    <div class="flex flex-col md:flex-row items-center gap-2">
                        <span class="font-bold">Order #{{ $order->id }}</span>
                        <span class="badge bg-yellow-200 text-yellow-800 px-2 py-1 rounded">Pending</span>
                        <span class="text-sm">Table: {{ $order->table->number ?? '-' }}</span>
                        <span class="text-sm">Items: {{ $order->items->pluck('menuItem.name')->join(', ') }}</span>
                        <span class="text-sm">Total: ${{ number_format($order->total_amount, 2) }}</span>
                        <span class="text-sm">Customer: {{ $order->customer->name ?? 'N/A' }} ({{ $order->customer->email ?? 'N/A' }})</span>
                    </div>
                    <form method="POST" action="{{ route('waiter.approve', $order) }}" onsubmit="return confirm('Approve this order?')">
                        @csrf
                        <button class="btn btn-primary px-3 py-1 rounded" type="submit" aria-label="Approve order #{{ $order->id }}" @if(!$order->canTransitionTo('confirmed')) disabled @endif>Approve</button>
                    </form>
                </li>
                @empty
                <li class="text-gray-500 p-4">No pending orders to show.</li>
                @endforelse
            </ul>
        </div>
        <div>
            <h2 class="text-lg font-semibold mb-2">Ready Orders to Serve</h2>
            <ul id="ready-orders" aria-label="Ready Orders">
                @forelse($readyOrders as $order)
                <li class="mb-2 p-2 bg-white rounded shadow flex flex-col md:flex-row justify-between items-center" tabindex="0">
                    <div class="flex flex-col md:flex-row items-center gap-2">
                        <span class="font-bold">Order #{{ $order->id }}</span>
                        <span class="badge bg-green-200 text-green-800 px-2 py-1 rounded">Ready</span>
                        <span class="text-sm">Table: {{ $order->table->number ?? '-' }}</span>
                        <span class="text-sm">Items: {{ $order->items->pluck('menuItem.name')->join(', ') }}</span>
                        <span class="text-sm">Total: ${{ number_format($order->total_amount, 2) }}</span>
                        <span class="text-sm">Customer: {{ $order->customer->name ?? 'N/A' }} ({{ $order->customer->email ?? 'N/A' }})</span>
                    </div>
                    <form method="POST" action="{{ route('waiter.serve', $order) }}" onsubmit="return confirm('Serve this order?')">
                        @csrf
                        <button class="btn btn-success px-3 py-1 rounded" type="submit" aria-label="Serve order #{{ $order->id }}" @if(!$order->canTransitionTo('delivered')) disabled @endif>Serve</button>
                    </form>
                </li>
                @empty
                <li class="text-gray-500 p-4">No ready orders to show.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
<script>
    setInterval(function() {
        fetch("{{ route('waiter.dashboard.data') }}")
            .then(response => response.json())
            .then(data => {
                let pendingHtml = '';
                if(data.pendingOrders.length === 0) {
                    pendingHtml = '<li class="text-gray-500 p-4">No pending orders to show.</li>';
                } else {
                    data.pendingOrders.forEach(order => {
                        pendingHtml += `<li class='mb-2 p-2 bg-white rounded shadow flex flex-col md:flex-row justify-between items-center' tabindex='0'><div class='flex flex-col md:flex-row items-center gap-2'><span class='font-bold'>Order #${order.id}</span><span class='badge bg-yellow-200 text-yellow-800 px-2 py-1 rounded'>Pending</span><span class='text-sm'>Table: ${order.table?.number ?? '-'}</span><span class='text-sm'>Items: ${order.items.map(i => i.menuItem.name).join(', ')}</span><span class='text-sm'>Total: $${order.total_amount.toFixed(2)}</span><span class='text-sm'>Customer: ${order.customer?.name ?? 'N/A'} (${order.customer?.email ?? 'N/A'})</span></div><form method='POST' action='/waiter/approve/${order.id}' onsubmit='return confirm("Approve this order?")'>@csrf<button class='btn btn-primary px-3 py-1 rounded' type='submit' aria-label='Approve order #${order.id}' ${!order.canTransitionToConfirmed ? 'disabled' : ''}>Approve</button></form></li>`;
                    });
                }
                document.getElementById('pending-orders').innerHTML = pendingHtml;
                let readyHtml = '';
                if(data.readyOrders.length === 0) {
                    readyHtml = '<li class="text-gray-500 p-4">No ready orders to show.</li>';
                } else {
                    data.readyOrders.forEach(order => {
                        readyHtml += `<li class='mb-2 p-2 bg-white rounded shadow flex flex-col md:flex-row justify-between items-center' tabindex='0'><div class='flex flex-col md:flex-row items-center gap-2'><span class='font-bold'>Order #${order.id}</span><span class='badge bg-green-200 text-green-800 px-2 py-1 rounded'>Ready</span><span class='text-sm'>Table: ${order.table?.number ?? '-'}</span><span class='text-sm'>Items: ${order.items.map(i => i.menuItem.name).join(', ')}</span><span class='text-sm'>Total: $${order.total_amount.toFixed(2)}</span><span class='text-sm'>Customer: ${order.customer?.name ?? 'N/A'} (${order.customer?.email ?? 'N/A'})</span></div><form method='POST' action='/waiter/serve/${order.id}' onsubmit='return confirm("Serve this order?")'>@csrf<button class='btn btn-success px-3 py-1 rounded' type='submit' aria-label='Serve order #${order.id}' ${!order.canTransitionToDelivered ? 'disabled' : ''}>Serve</button></form></li>`;
                    });
                }
                document.getElementById('ready-orders').innerHTML = readyHtml;
            });
    }, 5000);
</script>
@endsection
