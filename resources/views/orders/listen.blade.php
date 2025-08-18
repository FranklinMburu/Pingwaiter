@extends('layouts.app')

@section('title', 'Select Tables to Serve')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card p-0">
                <div
                    class="flex flex-col gap-2 sm:flex-row sm:justify-between px-4 py-5 sm:p-7 bg-gray-200/30 dark:bg-dark-card-two">
                    <h3 class="card-title text-3xl font-bold">Select Tables To Serve</h3>
                </div>
                <div class="card-datatable p-6">
                    <div id="message" class="w-100 mb-10" style="margin: 10px;"></div>
                    <div style="width:98%; margin-left:1%;">
                        <div class="table-responsive">
                            <table id="laravel-datatable-request"
                                class="display table-bordered table-striped table-hover dt-responsive mb-0 dataTable no-footer"
                                style="width: 100%;" role="grid" aria-describedby="laravel-datatable-request">
                                <thead>
                                    <tr>
                                        <th>SELECT</th>
                                        <th>#</th>
                                        <th>TABLE CODE</th>
                                        <th>TABLE SIZE</th>
                                        <th>LOCATION</th>
                                        <th>DESCRIPTION</th>
                                        <th>PICTURE</th>
                                    </tr>
                                    <tr class="filters text-xs">
                                        <th></th>
                                        <th></th>
                                        <th>
                                            <div class="relative">
                                                <i
                                                    class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                                <input type="text" placeholder="Search code"
                                                    class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                            </div>
                                        </th>
                                        <th>
                                            <div class="relative">
                                                <i
                                                    class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                                <input type="text" placeholder="Search size"
                                                    class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                            </div>
                                        </th>
                                        <th>
                                            <div class="relative">
                                                <i
                                                    class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                                <input type="text" placeholder="Search location"
                                                    class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                            </div>
                                        </th>
                                        <th>
                                            <div class="relative">
                                                <i
                                                    class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                                <input type="text" placeholder="Search description"
                                                    class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                            </div>
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            var ftable = $('#laravel-datatable-request').DataTable({
                destroy: true,
                processing: true,
                deferRender: true,
                scrollY: 400,
                scroller: true,
                searchHighlight: true,
                orderCellsTop: true,
                fixedHeader: true,
                dom: '<"flex items-center justify-between mb-4 gap-4"<"search-container"f><"length-container"l>>rt<"bottom flex justify-between items-center"<"info-container"i><"pagination-container"p>>',
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
                    url: '/gettables',
                    type: 'DELETE',
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
                    decorateGlobalSearch();
                    wireSearchHistory();
                },
                columns: [{
                        data: 'assignment',
                        name: 'assignment',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'table_code',
                        name: 'table_code'
                    },
                    {
                        data: 'size',
                        name: 'size'
                    },
                    {
                        data: 'location',
                        name: 'location'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'picture',
                        name: 'picture'
                    },
                ]
            });

            function decorateGlobalSearch() {
                const $filter = $('.search-container .dataTables_filter');
                if (!$filter.length) return;
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
                $filter.find('label').addClass('block w-64');
            }

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
                $('#laravel-datatable-request thead .filters input').each(function() {
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

            $('#laravel-datatable-request thead .filters input').on('keyup change', function() {
                const colIndex = $(this).parent().parent().index();
                $('#laravel-datatable-request').DataTable().column(colIndex).search(this.value).draw();
            });
        });

        $(document).on('change', '.table-assignment', function() {
            const tableId = $(this).data('table-id');
            const isAssigned = $(this).prop('checked');
            const checkbox = $(this);
            $.ajax({
                url: `/tables/${tableId}/toggle-assignment`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    assigned: isAssigned
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: isAssigned ? 'Table assigned successfully' :
                                'Table unassigned successfully',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Error updating table assignment',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    checkbox.prop('checked', !isAssigned);
                }
            });
        });
    </script>
@endpush
