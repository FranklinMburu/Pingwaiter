@extends('layouts.app')

@section('title', 'Customer Rewards')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
@endpush

@section('content')

    <div class="card p-0 min-h-screen">
        <div
            class="flex flex-col gap-2 sm:flex-row sm:justify-between px-4 py-5 sm:p-7 bg-gray-200/30 dark:bg-dark-card-two">
            <h3 class="card-title text-3xl font-bold text-heading dark:text-dark-text">All Customer Rewards</h3>
        </div>

        <!-- DataTable -->
        <div class="max-w-6xl mx-auto mt-10 p-6">
            <div class="overflow-x-auto">
                <table id="rewards-table" class="min-w-full table-auto text-sm text-left text-gray-700">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">Customer Name</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Total Amount</th>
                            <th class="px-4 py-2">Total Rewards</th>
                            <th class="px-4 py-2">Remarks</th>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#rewards-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('customer.rewards') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount'
                    },
                    {
                        data: 'total_rewards',
                        name: 'total_rewards'
                    },
                    {
                        data: 'remarks',
                        name: 'remarks'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>
@endpush
