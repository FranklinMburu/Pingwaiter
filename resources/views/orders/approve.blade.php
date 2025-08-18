@extends('layouts.app')

@section('title', 'Approve Orders')

@push('styles')
    {{-- Bootstrap Icons for search icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
@endpush

@section('content')
    <div class="grid grid-cols-12 gap-x-4">
        <div class="col-span-full">
            <div class="card p-0">
                <!-- Header -->
                <div
                    class="flex flex-col gap-2 sm:flex-row sm:justify-between px-4 py-5 sm:p-7 bg-gray-200/30 dark:bg-dark-card-two">
                    <h6 class="card-title text-3xl font-bold text-heading dark:text-dark-text">Approve Orders</h6>

                    <button id="bulk-approve-btn" class="btn b-solid btn-primary-solid">
                        Approve Selected
                    </button>
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
                                    <th>ACTION</th>
                                    <th>SELECT</th>
                                    <th>#</th>
                                    <th>ORDER NUMBER</th>
                                    <th>TABLE CODE</th>
                                    <th>FOOD CODE</th>
                                    <th>FOOD NAME</th>
                                    <th>STYLE</th>
                                    <th>QUANTITY</th>
                                    <th>PRICE</th>
                                    <th>REMARK</th>
                                    <th>STATUS</th>
                                    <th>DATE</th>
                                </tr>
                                <tr class="filters text-xs">
                                    <th></th>
                                    <th></th>
                                    <th></th>

                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search..."
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search..."
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search..."
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search..."
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search..."
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search..."
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search..."
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search..."
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search..."
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search..."
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-dark-border-three">
                                <!-- Rows go here -->
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
        const selectedOrders = new Set();

        // Handle checkbox toggle
        $(document).on('change', '.order-checkbox', function() {
            const id = $(this).val();
            if ($(this).is(':checked')) {
                selectedOrders.add(id);
            } else {
                selectedOrders.delete(id);
            }
        });

        // Handle "Select All"
        $(document).on('change', '#select-all', function() {
            const isChecked = $(this).is(':checked');
            $('.order-checkbox').each(function() {
                $(this).prop('checked', isChecked).trigger('change');
            });
        });

        var errorcolor = "#ffcccc";

        $(function() {
            cardSection = $('#page-block');
        });

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
                    url: '/getapproveorderlist',
                    type: 'GET',
                    beforeSend: function() {},
                    complete: function() {},
                },
                initComplete: function() {
                    const api = this.api();

                    // Column filters binding (unchanged)
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

                    // Global search icon + spacing
                    decorateGlobalSearch();

                    // Recent-search history for column filters
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

                            if (data.status === 'Pending' && is_waiter) {
                                html +=
                                    '<button class="btn btn-sm btn-primary mr-1" onclick="approveEditFn(' +
                                    data.id + ')" title="Approve order" data-id="' + data.id +
                                    '"><i class="fa fa-check"></i> Approve</button>';
                            }

                            html +=
                                '<button class="btn btn-sm btn-danger mr-1" onclick="orderDeleteFn(' +
                                data.id + ')" title="Delete order" data-id="' + data.id +
                                '"><i class="fa fa-trash"></i></button>';

                            let banClass = data.is_banned ? 'btn-secondary' : 'btn-dark';
                            let banIcon = data.is_banned ? 'fa-user-check' : 'fa-user-slash';
                            let banTitle = data.is_banned ? 'Unban customer' : 'Ban customer';

                            html += '<button class="btn btn-sm ' + banClass +
                                '" onclick="toggleBanCustomer(' + data.id + ')" title="' +
                                banTitle + '" data-id="' + data.id + '"><i class="fa ' + banIcon +
                                '"></i></button>';

                            html += '</div>';
                            return html;
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
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `<input type="checkbox" class="order-checkbox" value="${data.id}">`;
                        }
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
                    {
                        data: 'price',
                        name: 'price',
                        render: function(data) {
                            return '{{ currency_symbol() }}' + parseFloat(data).toFixed(2);
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
                    }
                ],
            });

            // Manual column search binding; use closest('th') because inputs are wrapped
            $('#laravel-datatable-order thead .filters input').on('keyup change', function() {
                let colIndex = $(this).closest('th').index();
                ftable.column(colIndex).search(this.value).draw();
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

        // ===== Existing actions (unchanged) =====
        setInterval(function() {
            $('#laravel-datatable-order').dataTable().fnDraw(false);
        }, 10000);

        function approveEditFn(record_id) {
            $.get('/approveorder/' + record_id, function(data) {
                if (data.success) {
                    alert_toast(data.success, 'success');
                    $('#laravel-datatable-order').dataTable().ajax.reload(null, false);
                } else {
                    alert_toast('An error occured please try again!', 'error');
                }
            });
        }

        function orderDeleteFn(record_id) {
            var check = confirm('Are u sure to delete this data?');
            if (check === true) {
                deleteOrder(record_id);
            }
        }

        function toggleBanCustomer(record_id) {
            var action = $('#laravel-datatable-order').DataTable().row($(`[data-id="${record_id}"]`).closest('tr')).data()
                .is_banned ? 'unban' : 'ban';
            var message = `Are you sure you want to ${action} this customer?`;

            var check = confirm(message);
            if (check === true) {
                $.get('/banCustomer/' + record_id, function(data) {
                    if (data.success) {
                        alert_toast(data.success, 'success');
                        var fTable = $('#laravel-datatable-order').dataTable();
                        fTable.fnDraw(false);
                    } else {
                        alert_toast('An error occured please try again!', 'error');
                    }
                })
            }
        }

        function deleteOrder(record_id) {
            $.get('/deleteorder/' + record_id, function(data) {
                if (data.success) {
                    alert_toast(data.success, 'success');
                    var fTable = $('#laravel-datatable-order').dataTable();
                    fTable.fnDraw(false);
                } else {
                    alert_toast('An error occured please try again!', 'error');
                }
            });
        }

        function selectFood() {
            $.get('/itemdetail/' + $('#item_id').val(), function(data) {
                if (data.item) {
                    $('#food_code').html(data.item.item_code);
                    $('#food_name').html(data.item.name);
                    $('#description').html(data.item.description);
                    if (data.item.picture) {
                        $('#food_image').attr('src', '/uploads/food/pictures/' + data.item.picture);
                    } else {
                        $('#food_image').attr('src', '');
                    }
                } else {
                    alert_toast('an error occured', 'error');
                }
            })
        }

        function submitToKitchen(record_id) {
            var check = confirm('Are u sure to send to kitchen this order?');
            if (check === true) {
                submitOrder(record_id);
            }
        }

        function submitOrder(record_id) {
            $.get('/submittokithcen/' + record_id, function(data) {
                if (data.success) {
                    alert_toast(data.success, 'success');
                    var fTable = $('#laravel-datatable-order').dataTable();
                    fTable.fnDraw(false);
                } else {
                    alert_toast('An error ocurred', 'error')
                }
            });
        }

        function removeNameValidation() {
            $('#name-error').html('');
        }

        function removeSlugValidation() {
            $('#slug-error').html('');
        }

        function closeModal() {
            $('#name').val('');
            $('#slug').val('');
            $('#name-error').html('');
            $('#slug-error').html('');
            $('#savebutton').html('Save');
        }
    </script>
@endpush
