@extends('layouts.app')

@section('title', 'Reporting')

@section('content')
    <div class="card p-0">
        {{-- Header --}}
        <div
            class="flex flex-col gap-2 sm:flex-row sm:justify-between px-4 py-5 sm:p-7 bg-gray-200/30 dark:bg-dark-card-two">
            <h3 class="card-title text-3xl font-bold text-heading dark:text-dark-text">Reporting</h3>
        </div>

        <div class="p-6 space-y-6">
            <div class="grid grid-cols-12 gap-6">
                {{-- Daily Sales --}}
                <div class="col-span-12 lg:col-span-6">
                    <div class="card p-0">
                        <div class="flex-center-between p-4 border-b border-gray-200 dark:border-dark-border">
                            <h6 class="mb-0">Daily Sales</h6>
                        </div>
                        <div class="p-4">
                            <table class="mx-auto table-auto border-collapse w-full max-w-xs">
                                <thead>
                                    <tr>
                                        <th class="dk-border-one p-2">Day</th>
                                        <th class="dk-border-one p-2">Month</th>
                                        <th class="dk-border-one p-2">Year</th>
                                        <th class="dk-border-one p-2">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="dk-border-one p-2">
                                            <input type="number" name="daily_day" id="daily_day" class="form-input w-full"
                                                value="{{ intval(date('d')) }}" />
                                        </td>
                                        <td class="dk-border-one p-2">
                                            <input type="number" name="daily_month" id="daily_month"
                                                class="form-input w-full" value="{{ intval(date('m')) }}" />
                                        </td>
                                        <td class="dk-border-one p-2">
                                            <input type="number" name="daily_year" id="daily_year"
                                                class="form-input w-full" value="{{ date('Y') }}" />
                                        </td>
                                        <td class="dk-border-one p-2 text-center" id="daily_total">0</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="p-4 text-center">
                            <button id="daily_filter_btn" onclick="filterDailySales()"
                                class="btn b-light btn-primary-light">
                                Filter
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Monthly Sales --}}
                <div class="col-span-12 lg:col-span-6">
                    <div class="card p-0">
                        <div class="flex-center-between p-4 border-b border-gray-200 dark:border-dark-border">
                            <h6 class="mb-0">Monthly Sales</h6>
                        </div>
                        <div class="p-4">
                            <table class="mx-auto table-auto border-collapse w-full max-w-xs">
                                <thead>
                                    <tr>
                                        <th class="dk-border-one p-2">Month</th>
                                        <th class="dk-border-one p-2">Year</th>
                                        <th class="dk-border-one p-2">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="dk-border-one p-2">
                                            <input type="number" name="monthly_month" id="monthly_month"
                                                class="form-input w-full" value="{{ intval(date('m')) }}" />
                                        </td>
                                        <td class="dk-border-one p-2">
                                            <input type="number" name="monthly_year" id="monthly_year"
                                                class="form-input w-full" value="{{ date('Y') }}" />
                                        </td>
                                        <td class="dk-border-one p-2 text-center" id="monthly_total">0</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="p-4 text-center">
                            <button id="monthly_filter_btn" onclick="filterMonthlySales()"
                                class="btn b-light btn-primary-light">
                                Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Yearly Sales --}}
            <div class="grid grid-cols-12">
                <div class="col-span-12 lg:col-span-4 lg:col-start-5">
                    <div class="card p-0">
                        <div class="flex-center-between p-4 border-b border-gray-200 dark:border-dark-border">
                            <h6 class="mb-0">Yearly Sales</h6>
                        </div>
                        <div class="p-4">
                            <table class="mx-auto table-auto border-collapse w-full max-w-xs">
                                <thead>
                                    <tr>
                                        <th class="dk-border-one p-2">Year</th>
                                        <th class="dk-border-one p-2">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="dk-border-one p-2">
                                            <input type="number" name="yearly_year" id="yearly_year"
                                                class="form-input w-full" value="{{ intval(date('Y')) }}" />
                                        </td>
                                        <td class="dk-border-one p-2 text-center" id="yearly_total">0</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="p-4 text-center">
                            <button id="yearly_filter_btn" onclick="filterYearlySales()"
                                class="btn b-light btn-primary-light">
                                Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- JS Script --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Load default data when page loads
        $(document).ready(function() {
            filterDailySales();
            filterMonthlySales();
            filterYearlySales();
        });

        function filterDailySales() {
            const day = $('#daily_day').val();
            const month = $('#daily_month').val();
            const year = $('#daily_year').val();

            // Input validation
            if (!day || !month || !year) {
                alert('Please fill in all fields (day, month, and year)');
                return;
            }

            // Add loading state
            $('#daily_filter_btn').prop('disabled', true);
            $('#daily_total').text('Loading...');

            $.ajax({
                url: '/report/daily-sales',
                method: 'GET',
                data: {
                    day,
                    month,
                    year
                },
                success: function(response) {
                    $('#daily_total').text(response.total ? response.total.toFixed(2) : '0.00');
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching daily sales:', error);
                    $('#daily_total').text('Error');
                    alert('Failed to fetch daily sales data');
                },
                complete: function() {
                    $('#daily_filter_btn').prop('disabled', false);
                }
            });
        }

        function filterMonthlySales() {
            const month = $('#monthly_month').val();
            const year = $('#monthly_year').val();

            // Add loading state
            $('#monthly_filter_btn').prop('disabled', true);
            $('#monthly_total').text('Loading...');

            $.ajax({
                url: '/report/monthly-sales',
                method: 'GET',
                data: {
                    month,
                    year
                },
                success: function(response) {
                    $('#monthly_total').text(response.total ? response.total.toFixed(2) : '0.00');
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching monthly sales:', error);
                    $('#monthly_total').text('Error');
                },
                complete: function() {
                    $('#monthly_filter_btn').prop('disabled', false);
                }
            });
        }

        function filterYearlySales() {
            const year = $('#yearly_year').val();

            // Add loading state
            $('#yearly_filter_btn').prop('disabled', true);
            $('#yearly_total').text('Loading...');

            $.ajax({
                url: '/report/yearly-sales',
                method: 'GET',
                data: {
                    year
                },
                success: function(response) {
                    $('#yearly_total').text(response.total ? response.total.toFixed(2) : '0.00');
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching yearly sales:', error);
                    $('#yearly_total').text('Error');
                },
                complete: function() {
                    $('#yearly_filter_btn').prop('disabled', false);
                }
            });
        }
    </script>
@endpush
