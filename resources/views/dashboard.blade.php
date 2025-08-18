@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
@endpush

@section('content')
    @if (auth()->user()->isRestaurant())
        <div class="grid grid-cols-12 gap-x-4">
            <!-- Period Selector and Download -->
            <div class="col-span-full card p-6 mb-4 dk-theme-card-square">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div>
                            <label class="block text-sm text-gray-500 dark:text-dark-text font-semibold mb-1">From
                                Date</label>
                            <input type="date" name="from_date"
                                class="form-input border dk-border-one px-3 py-2 text-gray-900 dark:text-dark-text">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500 dark:text-dark-text font-semibold mb-1">To
                                Date</label>
                            <input type="date" name="to_date"
                                class="form-input border dk-border-one px-3 py-2 text-gray-900 dark:text-dark-text">
                        </div>
                    </div>
                    <button id="download-excel"
                        class="btn b-solid btn-primary-solid btn-lg dk-theme-card-square flex items-center gap-2">
                        <i class="ri-download-line text-white"></i> Download All Records
                    </button>
                </div>
            </div>

            <!-- Turnover -->
            <div class="col-span-full card dk-theme-card-square">
                <h6 class="card-title p-6">Turnover</h6>
                <div class="grid grid-cols-12 gap-4 p-6 pt-0">
                    <div class="col-span-6 sm:col-span-3 dk-border-one p-4">
                        <h6 class="text-sm text-gray-500 dark:text-dark-text font-semibold">Total Sales</h6>
                        <div class="text-2xl font-semibold text-heading mt-2">{{ currency_symbol() }}{{ $totalSales }}
                        </div>
                    </div>
                    <div class="col-span-6 sm:col-span-3 dk-border-one p-4">
                        <h6 class="text-sm text-gray-500 dark:text-dark-text font-semibold">Total Customers</h6>
                        <div class="text-2xl font-semibold text-heading mt-2">{{ $totalCustomers }}</div>
                    </div>
                    <div class="col-span-6 sm:col-span-3 dk-border-one p-4">
                        <h6 class="text-sm text-gray-500 dark:text-dark-text font-semibold">Total Orders</h6>
                        <div class="text-2xl font-semibold text-heading mt-2">{{ $totalOrders }}</div>
                    </div>
                    <div class="col-span-6 sm:col-span-3 dk-border-one p-4">
                        <h6 class="text-sm text-gray-500 dark:text-dark-text font-semibold">Total Table Pings</h6>
                        <div class="text-2xl font-semibold text-heading mt-2">{{ $totalPings }}</div>
                    </div>
                </div>
            </div>

            <!-- Open Orders -->
            <div class="col-span-full lg:col-span-6 card dk-theme-card-square">
                <div class="flex-center-between p-6">
                    <h6 class="card-title">Open Orders</h6>
                    <div class="flex gap-2">
                        <button class="scroll-left btn b-solid btn-sm"><i class="ri-arrow-left-line"></i></button>
                        <button class="scroll-right btn b-solid btn-sm"><i class="ri-arrow-right-line"></i></button>
                    </div>
                </div>
                <div class="overflow-x-auto scrollbar-table px-6 pb-6">
                    <div class="flex gap-4 min-w-[600px]">
                        @foreach ($openOrders as $table)
                            <div class="w-1/3">
                                <h6 class="text-sm font-semibold mb-2">Table {{ $table['table_id'] }}
                                    ({{ $table['order_count'] }})
                                </h6>
                                <table class="table-auto w-full text-left text-xs text-gray-500 dark:text-dark-text">
                                    <thead class="border-b border-dashed border-gray-900/60 dark:border-dark-border-three">
                                        <tr>
                                            <th class="px-3.5 py-2">Table ID</th>
                                            <th class="px-3.5 py-2">Orders</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($table['orders'] as $order)
                                            <tr>
                                                <td class="px-3.5 py-2">{{ $order->table_id }}</td>
                                                <td class="px-3.5 py-2">1</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Orders Pending Approval -->
            <div class="col-span-full lg:col-span-6 card dk-theme-card-square">
                <div class="flex-center-between p-6">
                    <h6 class="card-title">Orders Pending Approval</h6>
                    <div class="flex gap-2">
                        <button class="scroll-left btn b-solid btn-sm"><i class="ri-arrow-left-line"></i></button>
                        <button class="scroll-right btn b-solid btn-sm"><i class="ri-arrow-right-line"></i></button>
                    </div>
                </div>
                <div class="overflow-x-auto scrollbar-table px-6 pb-6">
                    <div class="flex gap-4 min-w-[600px]">
                        @foreach ($pendingApproval as $table)
                            <div class="w-1/3">
                                <h6 class="text-sm font-semibold mb-2">Table {{ $table['table_id'] }}
                                    ({{ $table['order_count'] }})
                                </h6>
                                <table class="table-auto w-full text-left text-xs text-gray-500 dark:text-dark-text">
                                    <thead class="border-b border-dashed border-gray-900/60 dark:border-dark-border-three">
                                        <tr>
                                            <th class="px-3.5 py-2">Table ID</th>
                                            <th class="px-3.5 py-2">Orders</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($table['orders'] as $order)
                                            <tr>
                                                <td class="px-3.5 py-2">{{ $order->table_id }}</td>
                                                <td class="px-3.5 py-2">1</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Orders Pending Delivery -->
            <div class="col-span-full lg:col-span-6 card dk-theme-card-square">
                <div class="flex-center-between p-6">
                    <h6 class="card-title">Orders Pending Delivery</h6>
                    <div class="flex gap-2">
                        <button class="scroll-left btn b-solid btn-sm"><i class="ri-arrow-left-line"></i></button>
                        <button class="scroll-right btn b-solid btn-sm"><i class="ri-arrow-right-line"></i></button>
                    </div>
                </div>
                <div class="overflow-x-auto scrollbar-table px-6 pb-6">
                    <div class="flex gap-4 min-w-[600px]">
                        @foreach ($pendingDelivery as $table)
                            <div class="w-1/3">
                                <h6 class="text-sm font-semibold mb-2">Table {{ $table['table_id'] }}
                                    ({{ $table['order_count'] }})
                                </h6>
                                <table class="table-auto w-full text-left text-xs text-gray-500 dark:text-dark-text">
                                    <thead class="border-b border-dashed border-gray-900/60 dark:border-dark-border-three">
                                        <tr>
                                            <th class="px-3.5 py-2">Table ID</th>
                                            <th class="px-3.5 py-2">Orders</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($table['orders'] as $order)
                                            <tr>
                                                <td class="px-3.5 py-2">{{ $table['table_id'] }}</td>
                                                <td class="px-3.5 py-2">1</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Orders Pending Payment -->
            <div class="col-span-full lg:col-span-6 card dk-theme-card-square">
                <div class="flex-center-between p-6">
                    <h6 class="card-title">Orders Pending Payment</h6>
                    <div class="flex gap-2">
                        <button class="scroll-left btn b-solid btn-sm"><i class="ri-arrow-left-line"></i></button>
                        <button class="scroll-right btn b-solid btn-sm"><i class="ri-arrow-right-line"></i></button>
                    </div>
                </div>
                <div class="overflow-x-auto scrollbar-table px-6 pb-6">
                    <div class="flex gap-4 min-w-[600px]">
                        @foreach ($pendingPayment as $table)
                            <div class="w-1/3">
                                <h6 class="text-sm font-semibold mb-2">Table {{ $table['table_id'] }}
                                    ({{ $table['order_count'] }})
                                </h6>
                                <table class="table-auto w-full text-left text-xs text-gray-500 dark:text-dark-text">
                                    <thead class="border-b border-dashed border-gray-900/60 dark:border-dark-border-three">
                                        <tr>
                                            <th class="px-3.5 py-2">Table ID</th>
                                            <th class="px-3.5 py-2">Orders</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($table['orders'] as $order)
                                            <tr>
                                                <td class="px-3.5 py-2">{{ $table['table_id'] }}</td>
                                                <td class="px-3.5 py-2">1</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Active Table Pings -->
            <div class="col-span-full lg:col-span-6 card dk-theme-card-square">
                <div class="flex-center-between p-6">
                    <h6 class="card-title">Active Table Pings</h6>
                    <div class="flex gap-2">
                        <button class="scroll-left btn b-solid btn-sm"><i class="ri-arrow-left-line"></i></button>
                        <button class="scroll-right btn b-solid btn-sm"><i class="ri-arrow-right-line"></i></button>
                    </div>
                </div>
                <div class="overflow-x-auto scrollbar-table px-6 pb-6">
                    <div class="flex gap-4 min-w-[600px]">
                        @foreach ($activePings as $ping)
                            <div class="w-1/3">
                                <h6 class="text-sm font-semibold mb-2">Table {{ $ping['table_id'] }}
                                    ({{ $ping['ping_count'] }})
                                </h6>
                                <table class="table-auto w-full text-left text-xs text-gray-500 dark:text-dark-text">
                                    <thead class="border-b border-dashed border-gray-900/60 dark:border-dark-border-three">
                                        <tr>
                                            <th class="px-3.5 py-2">Table ID</th>
                                            <th class="px-3.5 py-2">Pings</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ping['pings'] as $p)
                                            <tr>
                                                <td class="px-3.5 py-2">{{ $p->table_id }}</td>
                                                <td class="px-3.5 py-2">1</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Top 3 Foods By Sales Value -->
            <div class="col-span-full lg:col-span-6 card dk-theme-card-square">
                <div class="flex-center-between p-6">
                    <h6 class="card-title">Top 3 Foods By Sales Value</h6>
                    <div class="flex gap-2">
                        <button class="scroll-left btn b-solid btn-sm"><i class="ri-arrow-left-line"></i></button>
                        <button class="scroll-right btn b-solid btn-sm"><i class="ri-arrow-right-line"></i></button>
                    </div>
                </div>
                <div class="overflow-x-auto scrollbar-table px-4 pb-6">
                    <div class="flex gap-4 min-w-[400px]">
                        @foreach ($topFoodsByValue as $food)
                            <div class="w-1/3 dk-border-one p-4">
                                <h6 class="text-sm font-semibold mb-2">{{ $food->name }}</h6>
                                <div class="text-xl text-heading font-semibold">
                                    {{ currency_symbol() }}{{ number_format($food->total_value, 2) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Top 3 Foods By Sales Quantity -->
            <div class="col-span-full lg:col-span-6 card dk-theme-card-square">
                <div class="flex-center-between p-6">
                    <h6 class="card-title">Top 3 Foods By Sales Quantity</h6>
                    <div class="flex gap-2">
                        <button class="scroll-left btn b-solid btn-sm"><i class="ri-arrow-left-line"></i></button>
                        <button class="scroll-right btn b-solid btn-sm"><i class="ri-arrow-right-line"></i></button>
                    </div>
                </div>
                <div class="overflow-x-auto scrollbar-table px-4 pb-6">
                    <div class="flex gap-4 min-w-[400px]">
                        @foreach ($topFoodsByQuantity as $food)
                            <div class="w-1/3 dk-border-one p-4">
                                <h6 class="text-sm font-semibold mb-2">{{ $food->name }}</h6>
                                <div class="text-xl text-heading font-semibold">{{ number_format($food->total_quantity) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Top 3 Tables By Sales Value -->
            <div class="col-span-full lg:col-span-6 card dk-theme-card-square">
                <div class="flex-center-between p-6">
                    <h6 class="card-title">Top 3 Tables By Sales Value</h6>
                    <div class="flex gap-2">
                        <button class="scroll-left btn b-solid btn-sm"><i class="ri-arrow-left-line"></i></button>
                        <button class="scroll-right btn b-solid btn-sm"><i class="ri-arrow-right-line"></i></button>
                    </div>
                </div>
                <div class="overflow-x-auto scrollbar-table px-4 pb-6">
                    <div class="flex gap-4 min-w-[400px]">
                        @foreach ($topTablesByValue as $table)
                            <div class="w-1/3 dk-border-one p-4">
                                <h6 class="text-sm font-semibold mb-2">Table {{ $table->table_id }}</h6>
                                <div class="text-xl text-heading font-semibold">
                                    {{ currency_symbol() }}{{ number_format($table->total_value, 2) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Top 3 Tables By Sales Quantity -->
            <div class="col-span-full lg:col-span-6 card dk-theme-card-square">
                <div class="flex-center-between p-6">
                    <h6 class="card-title">Top 3 Tables By Sales Quantity</h6>
                    <div class="flex gap-2">
                        <button class="scroll-left btn b-solid btn-sm"><i class="ri-arrow-left-line"></i></button>
                        <button class="scroll-right btn b-solid btn-sm"><i class="ri-arrow-right-line"></i></button>
                    </div>
                </div>
                <div class="overflow-x-auto scrollbar-table px-6 pb-6">
                    <div class="flex gap-4 min-w-[400px]">
                        @foreach ($topTablesByQuantity as $table)
                            <div class="w-1/3 dk-border-one p-4">
                                <h6 class="text-sm font-semibold mb-2">Table {{ $table->table_id }}</h6>
                                <div class="text-xl text-heading font-semibold">
                                    {{ number_format($table->total_quantity) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Top 3 Customers -->
            <div class="col-span-full lg:col-span-6 card dk-theme-card-square">
                <div class="flex-center-between p-6">
                    <h6 class="card-title">Top 3 Customers</h6>
                    <div class="flex gap-2">
                        <button class="scroll-left btn b-solid btn-sm"><i class="ri-arrow-left-line"></i></button>
                        <button class="scroll-right btn b-solid btn-sm"><i class="ri-arrow-right-line"></i></button>
                    </div>
                </div>
                <div class="overflow-x-auto scrollbar-table px-4 pb-6">
                    <div class="flex gap-4 min-w-[400px]">
                        @foreach ($topCustomersByValue as $customer)
                            <div class="w-1/3 dk-border-one p-4">
                                <h6 class="text-sm font-semibold mb-2">{{ $customer->name }}</h6>
                                <div class="text-xl text-heading font-semibold">
                                    {{ currency_symbol() }}{{ number_format($customer->total_value, 2) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Top 3 Waiters By Sales Value -->
            <div class="col-span-full lg:col-span-6 card dk-theme-card-square">
                <div class="flex-center-between p-6">
                    <h6 class="card-title">Top 3 Waiters By Sales Value</h6>
                    <div class="flex gap-2">
                        <button class="scroll-left btn b-solid btn-sm"><i class="ri-arrow-left-line"></i></button>
                        <button class="scroll-right btn b-solid btn-sm"><i class="ri-arrow-right-line"></i></button>
                    </div>
                </div>
                <div class="overflow-x-auto scrollbar-table px-6 pb-4">
                    <div class="flex gap-4 min-w-[400px]">
                        @foreach ($topWaitersByValue as $waiter)
                            <div class="w-1/3 dk-border-one p-4">
                                <h6 class="text-sm font-semibold mb-2">{{ $waiter->name }}</h6>
                                <div class="text-xl text-heading font-semibold">
                                    {{ currency_symbol() }}{{ number_format($waiter->total_value, 2) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Top 3 Waiters By Sales Quantity -->
            <div class="col-span-full lg:col-span-6 card dk-theme-card-square">
                <div class="flex-center-between p-6">
                    <h6 class="card-title">Top 3 Waiters By Sales Quantity</h6>
                    <div class="flex gap-2">
                        <button class="scroll-left btn b-solid btn-sm"><i class="ri-arrow-left-line"></i></button>
                        <button class="scroll-right btn b-solid btn-sm"><i class="ri-arrow-right-line"></i></button>
                    </div>
                </div>
                <div class="overflow-x-auto scrollbar-table px-6 pb-4">
                    <div class="flex gap-4 min-w-[400px]">
                        @foreach ($topWaitersByQuantity as $waiter)
                            <div class="w-1/3 dk-border-one p-4">
                                <h6 class="text-sm font-semibold mb-2">{{ $waiter->name }}</h6>
                                <div class="text-xl text-heading font-semibold">
                                    {{ number_format($waiter->total_quantity) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Top 3 Cooks By Sales Value -->
            <div class="col-span-full lg:col-span-6 card dk-theme-card-square">
                <div class="flex-center-between p-6">
                    <h6 class="card-title">Top 3 Cooks By Sales Value</h6>
                    <div class="flex gap-2">
                        <button class="scroll-left btn b-solid btn-sm"><i class="ri-arrow-left-line"></i></button>
                        <button class="scroll-right btn b-solid btn-sm"><i class="ri-arrow-right-line"></i></button>
                    </div>
                </div>
                <div class="overflow-x-auto scrollbar-table px-6 pb-4">
                    <div class="flex gap-4 min-w-[400px]">
                        @foreach ($topCooksByValue as $cook)
                            <div class="w-1/3 dk-border-one p-4">
                                <h6 class="text-sm font-semibold mb-2">{{ $cook->name }}</h6>
                                <div class="text-xl text-heading font-semibold">
                                    {{ currency_symbol() }}{{ number_format($cook->total_value, 2) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Top 3 Cooks By Sales Quantity -->
            <div class="col-span-full lg:col-span-6 card dk-theme-card-square">
                <div class="flex-center-between p-6">
                    <h6 class="card-title">Top 3 Cooks By Sales Quantity</h6>
                    <div class="flex gap-2">
                        <button class="scroll-left btn b-solid btn-sm"><i class="ri-arrow-left-line"></i></button>
                        <button class="scroll-right btn b-solid btn-sm"><i class="ri-arrow-right-line"></i></button>
                    </div>
                </div>
                <div class="overflow-x-auto scrollbar-table px-6 pb-6">
                    <div class="flex gap-4 min-w-[400px]">
                        @foreach ($topCooksByQuantity as $cook)
                            <div class="w-1/3 dk-border-one p-4">
                                <h6 class="text-sm font-semibold mb-2">{{ $cook->name }}</h6>
                                <div class="text-xl text-heading font-semibold">{{ number_format($cook->total_quantity) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Top 3 Cashiers By Sales Value -->
            <div class="col-span-full lg:col-span-6 card dk-theme-card-square">
                <div class="flex-center-between p-6">
                    <h6 class="card-title">Top 3 Cashiers By Sales Value</h6>
                    <div class="flex gap-2">
                        <button class="scroll-left btn b-solid btn-sm"><i class="ri-arrow-left-line"></i></button>
                        <button class="scroll-right btn b-solid btn-sm"><i class="ri-arrow-right-line"></i></button>
                    </div>
                </div>
                <div class="overflow-x-auto scrollbar-table px-6 pb-4">
                    <div class="flex gap-4 min-w-[400px]">
                        @foreach ($topCashiersByValue as $cashier)
                            <div class="w-1/3 dk-border-one p-4">
                                <h6 class="text-sm font-semibold mb-2">{{ $cashier->name }}</h6>
                                <div class="text-xl text-heading font-semibold">
                                    {{ currency_symbol() }}{{ number_format($cashier->total_value, 2) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Top 3 Cashiers By Sales Quantity -->
            <div class="col-span-full lg:col-span-6 card dk-theme-card-square">
                <div class="flex-center-between p-6">
                    <h6 class="card-title">Top 3 Cashiers By Sales Quantity</h6>
                    <div class="flex gap-2">
                        <button class="scroll-left btn b-solid btn-sm"><i class="ri-arrow-left-line"></i></button>
                        <button class="scroll-right btn b-solid btn-sm"><i class="ri-arrow-right-line"></i></button>
                    </div>
                </div>
                <div class="overflow-x-auto scrollbar-table px-6 pb-6">
                    <div class="flex gap-4 min-w-[400px]">
                        @foreach ($topCashiersByQuantity as $cashier)
                            <div class="w-1/3 dk-border-one p-4">
                                <h6 class="text-sm font-semibold mb-2">{{ $cashier->name }}</h6>
                                <div class="text-xl text-heading font-semibold">
                                    {{ number_format($cashier->total_quantity) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script>
        // Horizontal scrolling
        document.querySelectorAll('.scroll-left').forEach(btn => {
            btn.addEventListener('click', () => {
                const container = btn.closest('.card').querySelector('.overflow-x-auto');
                container.scrollBy({
                    left: -200,
                    behavior: 'smooth'
                });
            });
        });

        document.querySelectorAll('.scroll-right').forEach(btn => {
            btn.addEventListener('click', () => {
                const container = btn.closest('.card').querySelector('.overflow-x-auto');
                container.scrollBy({
                    left: 200,
                    behavior: 'smooth'
                });
            });
        });

        // Excel download
        document.getElementById('download-excel')?.addEventListener('click', () => {
            const wb = XLSX.utils.book_new(); // Declare workbook

            const data = {
                'Open Orders': [{
                        Table: 'A1',
                        Orders: 2
                    },
                    {
                        Table: 'A2',
                        Orders: 3
                    },
                    {
                        Table: 'B1',
                        Orders: 1
                    },
                    {
                        Table: 'B2',
                        Orders: 2
                    },
                    {
                        Table: 'C1',
                        Orders: 1
                    },
                    {
                        Table: 'C2',
                        Orders: 1
                    }
                ],
                'Orders Pending Approval': [{
                        Table: 'A1',
                        Orders: 2
                    },
                    {
                        Table: 'A2',
                        Orders: 3
                    },
                    {
                        Table: 'B1',
                        Orders: 1
                    },
                    {
                        Table: 'B2',
                        Orders: 2
                    },
                    {
                        Table: 'C1',
                        Orders: 1
                    },
                    {
                        Table: 'C2',
                        Orders: 1
                    }
                ],
                'Turnover': [{
                        Metric: 'Total Sales',
                        Value: '$405'
                    },
                    {
                        Metric: 'Total Customers',
                        Value: '4905'
                    },
                    {
                        Metric: 'Total Orders',
                        Value: '3344'
                    },
                    {
                        Metric: 'Total Table Pings',
                        Value: '466'
                    }
                ],
                'Top 3 Foods By Sales Value': [{
                        Item: 'Pizza',
                        Value: '$900'
                    },
                    {
                        Item: 'Burger',
                        Value: '$200'
                    },
                    {
                        Item: 'Lasagna',
                        Value: '$80'
                    }
                ]
            };

            Object.keys(data).forEach(sheetName => {
                const worksheet = XLSX.utils.json_to_sheet(data[sheetName]);
                XLSX.utils.book_append_sheet(wb, worksheet, sheetName);
            });

            XLSX.writeFile(wb, 'dashboard_data.xlsx');
        });
    </script>
@endpush
