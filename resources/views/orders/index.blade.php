@extends('layouts.app')

@section('title', 'Table Orders')

@section('content')
    <div class="grid grid-cols-12 gap-x-4">
        <div class="col-span-full">
            <div class="card p-0">
                <!-- Header -->
                <div
                    class="flex flex-col gap-2 sm:flex-row sm:justify-between px-4 py-5 sm:p-7 bg-gray-200/30 dark:bg-dark-card-two">
                    <h6 class="card-title text-3xl font-bold text-heading dark:text-dark-text">View Table Orders</h6>

                    <div class="flex flex-wrap gap-2 mb-4">
                        <button id="mark-prepared" type="button"
                            class="btn btn-sm btn-success b-solid btn-success-solid text-white">
                            <i class="fa fa-utensils mr-1"></i> Mark as Prepared
                        </button>
                        <button id="mark-delivered" type="button"
                            class="btn btn-sm btn-info b-solid btn-info-solid text-white">
                            <i class="fa fa-truck mr-1"></i> Mark as Delivered
                        </button>
                        <button id="mark-paid" type="button"
                            class="btn btn-sm btn-warning b-solid btn-warning-solid text-white">
                            <i class="fa fa-money-bill mr-1"></i> Mark as Paid
                        </button>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    <div id="message" class="w-full mb-4"></div>

                    @if (session('success'))
                        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table id="laravel-datatable-order"
                            class="table-auto border-collapse w-full whitespace-nowrap text-left text-gray-500 dark:text-dark-text font-medium">
                            <thead class="bg-[#F2F4F9] dark:bg-dark-card-two">
                                <tr>
                                    <th class="px-6 py-4 dk-border-one">ACTION</th>
                                    <th class="px-6 py-4 dk-border-one">SELECT</th>
                                    <th class="px-6 py-4 dk-border-one w-[1%]">#</th>
                                    <th class="px-6 py-4 dk-border-one font-bold">ORDER NUMBER</th>
                                    <th class="px-6 py-4 dk-border-one font-bold">TABLE CODE</th>
                                    <th class="px-6 py-4 dk-border-one">FOOD CODE</th>
                                    <th class="px-6 py-4 dk-border-one">FOOD NAME</th>
                                    <th class="px-6 py-4 dk-border-one">STYLE</th>
                                    <th class="px-6 py-4 dk-border-one">QUANTITY</th>
                                    <th class="px-6 py-4 dk-border-one">PRICE</th>
                                    <th class="px-6 py-4 dk-border-one">REMARK</th>
                                    <th class="px-6 py-4 dk-border-one">STATUS</th>
                                    <th class="px-6 py-4 dk-border-one">DATE</th>
                                    <th class="px-6 py-4 dk-border-one">APPROVED BY</th>
                                    <th class="px-6 py-4 dk-border-one">PREPARED BY</th>
                                    <th class="px-6 py-4 dk-border-one">DELIVERED BY</th>
                                    <th class="px-6 py-4 dk-border-one">PAID BY</th>
                                </tr>
                                <tr class="filters text-xs">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search"
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search"
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search"
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search"
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search"
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search"
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search"
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search"
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search"
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search"
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search"
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search"
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search"
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search"
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-dark-border-three">
                                <!-- Table rows rendered via JS or Blade -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var errorcolor = "#ffcccc";
        $(function() {
            cardSection = $('#page-block');
        });
        /* BEGIN: Display food menu table using yajra datatable */
        $(document).ready(function() {
            var ftable = $('#laravel-datatable-order').DataTable({
                destroy: true,
                processing: true,
                // serverSide: true,
                deferRender: true,
                scrollY: 400,
                scroller: true,
                searchHighlight: true,
                orderCellsTop: true,
                fixedHeader: true,
                dom: '<"flex items-center justify-between mb-4"<"search-container"f><"length-container"l>>rt<"bottom flex justify-between items-center"<"info-container"i><"pagination-container"p>>',
                lengthMenu: [
                    [10, 25, 50, 500, -1],
                    [10, 25, 50, 500, "All"]
                ],
                language: {
                    search: '',
                    searchPlaceholder: "Search here"
                },
                ajax: {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/getapprovedorderlist',
                    type: 'GET',
                    beforeSend: function() {

                    },
                    complete: function() {

                    },
                },
                initComplete: function() {
                    const api = this.api();

                    api.columns().every(function() {
                        const column = this;
                        $('input', $('.filters th').eq(column.index()))
                            .off()
                            .on('keyup change clear', function() {
                                if (column.search() !== this.value) {
                                    column.search(this.value).draw();
                                }
                            });
                    });

                    // Add icon + spacing to the global search
                    decorateGlobalSearch();

                    // Recent-search history (per-column)
                    wireSearchHistory();
                },
                columns: [{
                        data: null,
                        render: function(data, type, full, meta) {
                            let user_role = '{{ auth()->user()->role }}';
                            let is_waiter = user_role === 'waiter' || user_role === 'restaurant';
                            let is_cashier = user_role === 'cashier' || user_role === 'restaurant';
                            let is_cook = user_role === 'cook' || user_role === 'restaurant';

                            let html = '<div class="btn-group">';

                            // Check if the status is 'Prepared' and user is waiter
                            if (data.status === 'Prepared' && is_waiter) {
                                html +=
                                    '<button class="btn btn-sm btn-info mr-1" onclick="deliverFood(' +
                                    data.id + ')" ' +
                                    'title="Food delivered" data-id="' + data.id + '">' +
                                    '<i class="fa fa-truck"></i> Delivered</button>';
                            }

                            // Check if the status is 'Approved' and user is cook
                            if (data.status === 'Approved' && is_cook) {
                                html +=
                                    '<button class="btn btn-sm btn-success mr-1" onclick="prepareFood(' +
                                    data.id + ')" ' +
                                    'title="Food prepared" data-id="' + data.id + '">' +
                                    '<i class="fa fa-utensils"></i> Prepared</button>';
                            }

                            // Check if the status is 'Delivered' and user is cashier
                            if (data.status === 'Delivered' && is_cashier) {
                                html +=
                                    '<button class="btn btn-sm btn-warning mr-1" onclick="completeOrder(' +
                                    data.id + ')" ' +
                                    'title="Mark as paid" data-id="' + data.id + '">' +
                                    '<i class="fa fa-money-bill"></i> Paid</button>';
                            }

                            // Delete button
                            html +=
                                '<button class="btn btn-sm btn-danger mr-1" onclick="orderDeleteFn(' +
                                data.id + ')" ' +
                                'title="Delete order" data-id="' + data.id + '">' +
                                '<i class="fa fa-trash"></i></button>';

                            // Ban/Unban button
                            let banClass = data.is_banned ? 'btn-secondary' : 'btn-dark';
                            let banIcon = data.is_banned ? 'fa-user-check' : 'fa-user-slash';
                            let banTitle = data.is_banned ? 'Unban customer' : 'Ban customer';

                            html += '<button class="btn btn-sm ' + banClass +
                                '" onclick="toggleBanCustomer(' + data.id + ')" ' +
                                'title="' + banTitle + '" data-id="' + data.id + '">' +
                                '<i class="fa ' + banIcon + '"></i></button>';

                            html += '</div>';
                            return html;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            return `<input type="checkbox" class="order-checkbox" value="${data}">`;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'group_number',
                        name: 'group_number',
                        className: 'text-center'
                    },
                    {
                        data: 'table.table_code',
                        name: 'table.table_code',
                        className: 'text-center'
                    },
                    {
                        data: 'item.item_code',
                        name: 'item.item_code'
                    },
                    {
                        data: 'item.name',
                        name: 'item.name'
                    },
                    {
                        data: 'style',
                        name: 'style'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    // {
                    //     data: 'customer',
                    //     name: 'customer'
                    // },
                    {
                        data: 'price',
                        name: 'price',
                        render: function(data, type, row) {
                            return '{{ currency_symbol() }}' + parseFloat(data).toFixed(
                                2);
                        }
                    },
                    {
                        data: 'remark',
                        name: 'remark'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'approved_by',
                        name: 'approved_by'
                    },
                    {
                        data: 'prepared_by',
                        name: 'prepared_by'
                    },
                    {
                        data: 'delivered_by',
                        name: 'delivered_by'
                    },
                    {
                        data: 'completed_by',
                        name: 'completed_by'
                    }
                ],
            });

            // Keep your manual binding but make it wrapper-safe
            $('#laravel-datatable-order thead .filters input').on('keyup change', function() {
                let index = $(this).closest('th').index(); // use closest('th') because inputs are wrapped
                $('#laravel-datatable-order').DataTable().column(index).search(this.value).draw();
            });
        });

        // === Global search: add icon and input spacing ===
        function decorateGlobalSearch() {
            const $filter = $('.search-container .dataTables_filter');
            if (!$filter.length) return;

            // Remove "Search:" text node
            $filter.find('label').contents().filter(function() {
                return this.nodeType === 3;
            }).remove();

            const $input = $filter.find('input');
            $input
                .addClass('form-input w-full !pl-8 bg-white placeholder-gray-400')
                .attr('placeholder', 'Search the whole table')
                .wrap('<div class="relative"></div>')
                .parent()
                .prepend(
                    '<i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>'
                    );

            $filter.find('label').addClass('block w-64'); // optional width
        }

        // === Recent-search history (per-column) ===
        function wireSearchHistory() {
            const maxHistory = 5;

            function saveSearchHistory(colIndex, value) {
                if (!value) return;
                let key = `searchHistory_${colIndex}`;
                let history = JSON.parse(localStorage.getItem(key) || '[]');
                history = history.filter(item => item !== value);
                history.unshift(value);
                history = history.slice(0, maxHistory);
                localStorage.setItem(key, JSON.stringify(history));
            }

            function showSearchHistory(input, colIndex) {
                let key = `searchHistory_${colIndex}`;
                let history = JSON.parse(localStorage.getItem(key) || '[]');
                if (!history.length) return;

                // Remove any existing dropdown
                $(input).siblings('.search-history-dd').remove();

                let dropdown = document.createElement('div');
                dropdown.className =
                    'search-history-dd absolute z-50 bg-white border border-gray-200 rounded shadow-lg w-full mt-1';
                dropdown.innerHTML = history.map(item =>
                    `<div class="px-3 py-1 hover:bg-gray-100 cursor-pointer">${item}</div>`
                ).join('');

                dropdown.addEventListener('click', function(e) {
                    if (e.target && e.target.matches('div')) {
                        input.value = e.target.textContent;
                        $(input).trigger('change');
                        dropdown.remove();
                    }
                });

                input.parentNode.appendChild(dropdown);

                // Close on outside click
                document.addEventListener('click', function handler(ev) {
                    if (!dropdown.contains(ev.target)) {
                        dropdown.remove();
                        document.removeEventListener('click', handler);
                    }
                });
            }

            // Bind to each column filter
            $('#laravel-datatable-order thead .filters input').each(function() {
                $(this).on('keyup change', function() {
                    const colIndex = $(this).closest('th').index();
                    saveSearchHistory(colIndex, this.value.trim());
                });
                $(this).on('focus', function() {
                    const colIndex = $(this).closest('th').index();
                    showSearchHistory(this, colIndex);
                });
            });
        }
    </script>
@endpush
