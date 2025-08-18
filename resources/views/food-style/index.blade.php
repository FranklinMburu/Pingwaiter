@extends('layouts.app')

@section('title', 'Food Styles')

@section('content')
    <div class="grid grid-cols-12 gap-x-4">
        <div class="col-span-full">
            <div class="card p-0">
                <!-- Header -->
                <div
                    class="flex flex-col gap-2 sm:flex-row sm:justify-between px-4 py-5 sm:p-7 bg-gray-200/30 dark:bg-dark-card-two">
                    <h3 class="card-title text-3xl font-bold text-heading dark:text-dark-texte">Food Styles Management</h3>
                    <!-- Add Category button -->
                    <div class="flex justify-end">
                        <button type="button" onclick="openModal('Add Food Style')"
                            class="btn b-solid btn-primary-solid dk-theme-card-square">
                            <i class="ri-add-fill text-white  text-[18px] mr-1"></i> Add Style
                        </button>
                    </div>

                    <div class="flex space-x-3">
                        <!-- Upload Trigger -->
                        <button type="button" onclick="$('#uploadStyleFile').click()"
                            class="btn b-solid btn-primary-solid dk-theme-card-square">
                            <i class="ri-upload-cloud-line text-white text-lg mr-1"></i> Upload
                        </button>
                        <input type="file" id="uploadStyleFile" class="hidden" accept=".csv,.xlsx" />

                        <!-- Download Link -->
                        <a href="{{ route('food.styles.export') }}"
                            class="btn b-solid btn-primary-solid dk-theme-card-square">
                            <i class="ri-download-cloud-line text-white text-lg mr-1"></i> Download
                        </a>
                    </div>

                </div>

                <div class="p-6 space-y-4">
                    <div id="message" class="text-sm text-green-600"></div>

                    <div class="overflow-x-auto">
                        <table id="laravel-datatable-foodstyle"
                            class="table-auto border-collapse w-full whitespace-nowrap text-left text-gray-500 dark:text-dark-text font-medium">
                            <thead class="bg-[#F2F4F9] dark:bg-dark-card-two">
                                <tr>
                                    <th class="p-6 py-4 dk-border-one w-[20%]">Actions</th>
                                    <th class="p-6 py-4 dk-border-one w-[5%]">#</th>
                                    <th class="p-6 py-4 dk-border-one w-[75%]">Name</th>
                                </tr>
                                <tr class="filters text-xs">
                                    <th></th>
                                    <th></th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search name"
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400 px-2 py-1 rounded text-sm border" />
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-dark-border-three">
                                {{-- DataTables injects rows --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tailwind Modal -->
    <div id="style-modal" class="fixed inset-0 z-modal flex-center !hidden bg-black bg-opacity-50 modal">
        <div
            class="modal-content bg-white rounded-lg shadow-lg w-full max-w-lg transform transition-all duration-300 opacity-0 -translate-y-10 m-4 border-l-8 border-primary">
            <form id="styleForm" class="space-y-4 p-6">
                @csrf
                <h2 class="text-xl font-semibold">Add Food Style</h2>
                <input type="hidden" id="style_id" name="style_id" value="add">

                <div>
                    <label for="name" class="form-label"><strong>Style Name</strong> <span
                            class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" placeholder="Enter food style name"
                        class="form-input mt-1 w-full" required />
                    <small id="name-error" class="text-danger hidden mt-1"></small>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" class="btn b-light btn-secondary-light close-modal-btn">Close</button>
                    <button type="submit" class="btn b-solid btn-primary-solid">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    {{-- Bootstrap Icons for the search icon --}}
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/scroller/2.3.0/js/dataTables.scroller.min.js"></script>
    <script>
        $(document).ready(function() {
            window.openModal = function(title) {
                $('#styleForm')[0].reset();
                $('#style_id').val('add');
                $('#name-error').text('').addClass('hidden');
                $('#name').removeClass('border-red-500');
                if (title) $('#style-modal h2').text(title);
                const $m = $('#style-modal');
                $m.removeClass('!hidden');
                setTimeout(() => {
                    $m.find('.modal-content').removeClass('opacity-0 -translate-y-10');
                }, 10);
            }

            function closeModal() {
                const $m = $('#style-modal');
                $m.find('.modal-content').addClass('opacity-0 -translate-y-10');
                setTimeout(() => {
                    $m.addClass('!hidden');
                }, 300);
            }

            const table = $('#laravel-datatable-foodstyle').DataTable({
                destroy: true,
                processing: true,
                // serverSide: true,
                deferRender: true,
                scrollY: 400,
                scroller: true,
                orderCellsTop: true,
                fixedHeader: true,
                dom: '<"flex items-center justify-between mb-4"<"search-container"f><"length-container"l>>rt<"bottom flex justify-between items-center"<"info-container"i><"pagination-container"p>>',
                ajax: "{{ route('food.styles.get') }}",
                initComplete: function() {
                    $('.dataTables_length select').addClass('form-input').removeClass(
                        'form-control form-control-sm');
                    $('.dataTables_filter input').addClass('form-input').removeClass(
                        'form-control form-control-sm');

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

                    // Add icon to global search + spacing
                    decorateGlobalSearch();

                    // Recent-search history (per column)
                    wireSearchHistory();
                },
                drawCallback: function() {
                    $('.dataTables_length select, .dataTables_filter input')
                        .addClass('form-input')
                        .removeClass('form-control form-control-sm');
                    $('.dataTables_paginate .pagination')
                        .addClass('flex items-center space-x-1')
                        .find('li a')
                        .addClass('size-8 flex-center rounded-[4px] dk-border-one')
                        .filter('.current')
                        .addClass('bg-primary-500 text-white');
                },
                columns: [{
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'px-6 py-4 dk-border-one text-center',
                        render: function(data, type, row) {
                            return `
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" class="btn btn-sm btn-info edit-btn" data-id="${row.id}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>`;
                        }
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'px-6 py-4 dk-border-one'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        className: 'px-6 py-4 dk-border-one'
                    }
                ],
                order: [
                    [2, 'asc']
                ],
                responsive: true,
                autoWidth: false,
                language: {
                    lengthMenu: "_MENU_ entries per page",
                    search: "",
                    searchPlaceholder: "Search styles...",
                    processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
                    emptyTable: 'No food styles found',
                    zeroRecords: 'No matching food styles found'
                },
                createdRow: function(row) {
                    $(row).addClass('bg-white dark:bg-dark-card-two');
                }
            });

            // Column filter binding (wrapper-safe)
            $('#laravel-datatable-foodstyle thead .filters input').on('keyup change', function() {
                const colIndex = $(this).closest('th').index();
                table.column(colIndex).search(this.value).draw();
            });

            $(document).on('click', '[data-modal-id="style-modal"]', () => openModal('Add Food Style'));
            $(document).on('click', '#style-modal .close-modal-btn', closeModal);

            $('#styleForm').on('submit', function(e) {
                e.preventDefault();
                const id = $('#style_id').val();
                $.post(`/createupdatefoodstyle/${id}`, $(this).serialize())
                    .done(res => {
                        if (res.success) {
                            closeModal();
                            table.ajax.reload(null, false);
                            toastr.success(res.success);
                        }
                    })
                    .fail(xhr => {
                        if (xhr.status === 422) {
                            const err = xhr.responseJSON.errors.name?.[0] || '';
                            $('#name-error').text(err).removeClass('hidden');
                            $('#name').addClass('border-red-500');
                        } else {
                            toastr.error('An error occurred while processing your request.');
                        }
                    });
            });

            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                $.get(`/editfoodstyle/${id}`)
                    .done(res => {
                        if (res && res.style) {
                            openModal('Edit Food Style');
                            $('#style_id').val(res.style.id);
                            $('#name').val(res.style.name);
                        } else {
                            toastr.error('Style data is missing or invalid.');
                        }
                    })
                    .fail(() => {
                        toastr.error('Failed to load food style details.');
                    });
            });

            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This food style will be deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then(result => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/deletefoodstyle/${id}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success(res) {
                                if (res.success) {
                                    table.ajax.reload(null, false);
                                    toastr.success(res.success);
                                }
                            },
                            error() {
                                toastr.error('Failed to delete food style.');
                            }
                        });
                    }
                });
            });
        });

        $('#uploadStyleFile').on('change', function() {
            const file = this.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('file', file);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: "{{ route('food.styles.import') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success(res) {
                    if (res.success) {
                        toastr.success(res.success);
                        $('#laravel-datatable-foodstyle').DataTable().ajax.reload(null, false);
                    }
                },
                error(err) {
                    toastr.error(err.responseJSON?.message || 'Upload failed.');
                }
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
                .attr('placeholder', 'Search all styles')
                .wrap('<div class="relative"></div>')
                .parent()
                .prepend(
                    '<i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>'
                    );

            $filter.find('label').addClass('block w-64'); // optional fixed width
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

            // Bind to each column filter (right now only the Name column has an input)
            $('#laravel-datatable-foodstyle thead .filters input').each(function() {
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
