@extends('layouts.app')

@section('title', 'Food Categories')

@section('content')
    <div class="grid grid-cols-12 gap-x-4">
        <div class="col-span-full">
            <div class="card p-0">
                <!-- Header -->
                <div
                    class="flex flex-col gap-2 sm:flex-row sm:justify-between px-4 py-5 sm:p-7 bg-gray-200/30 dark:bg-dark-card-two">
                    <h6 class="card-title text-3xl font-bold text-heading dark:text-dark-text">Food Categories Management
                    </h6>
                    <!-- Add Category button -->
                    <div class="flex justify-end">
                        <button type="button" onclick="openCategoryModal('Add Food Category')"
                            class="btn b-solid btn-primary-solid dk-theme-card-square">
                            <i class="ri-add-fill text-white text-[18px] mr-1"></i> Add Category
                        </button>
                    </div>

                    <!-- Upload / Download buttons -->
                    <div class="flex space-x-3">
                        <button type="button" onclick="$('#uploadFileInput').click()"
                            class="btn b-solid btn-primary-solid dk-theme-card-square">
                            <i class="ri-upload-cloud-2-line text-lg text-white mr-1"></i> Upload
                        </button>
                        <input type="file" id="uploadFileInput" class="hidden" accept=".csv,.xlsx" />

                        <a href="{{ route('food.categories.export') }}"
                            class="btn b-solid btn-primary-solid dk-theme-card-square">
                            <i class="ri-download-cloud-2-line text-lg text-white mr-1"></i> Download
                        </a>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    <!-- Flash / AJAX messages -->
                    <div id="message" class="text-sm text-green-600"></div>

                    <!-- Table wrapper -->
                    <div class="overflow-x-auto">
                        <table id="laravel-datatable-foodcategory"
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
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400 px-2 py-1 rounded text-sm" />
                                        </div>
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 dark:divide-dark-border-three">
                                {{-- DataTables will inject rows here --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Modal -->
    <div id="category-modal" class="fixed inset-0 z-modal flex-center !hidden bg-black bg-opacity-50 modal">
        <div
            class="modal-content bg-white rounded-lg shadow-lg w-full max-w-lg transform transition-all duration-300 opacity-0 -translate-y-10 m-4 border-l-8 border-primary">
            <form id="categoryForm" class="space-y-4 p-6">
                @csrf
                <h2 class="text-xl font-semibold">Add Food Category</h2>
                <input type="hidden" id="category_id" name="category_id" value="add">

                <div>
                    <label for="name" class="form-label"><strong>Category Name</strong> <span
                            class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" placeholder="Enter category name"
                        class="form-input mt-1 w-full" required />
                    <small id="name-error" class="text-danger hidden mt-1"></small>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" class="btn b-light btn-secondary-light close-modal-btn">
                        Close
                    </button>
                    <button type="submit" class="btn b-solid btn-primary-solid">
                        Save
                    </button>
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
    <script>
        $(document).ready(function() {
            window.openCategoryModal = function(title) {
                $('#categoryForm')[0].reset();
                $('#category_id').val('add');
                $('#name-error').text('').addClass('hidden');
                $('#name').removeClass('border-red-500');
                $('#category-modal h2').text(title || 'Add Food Category');
                const $m = $('#category-modal');
                $m.removeClass('!hidden');
                setTimeout(() => {
                    $m.find('.modal-content').removeClass('opacity-0 -translate-y-10');
                }, 10);
            };

            function closeCategoryModal() {
                const $m = $('#category-modal');
                $m.find('.modal-content').addClass('opacity-0 -translate-y-10');
                setTimeout(() => {
                    $m.addClass('!hidden');
                }, 300);
            }

            // Init DataTable
            const table = $('#laravel-datatable-foodcategory').DataTable({
                destroy: true,
                processing: true,
                // serverSide: true,
                deferRender: true,
                scrollY: 400,
                scroller: true,
                orderCellsTop: true,
                fixedHeader: true,
                dom: '<"flex items-center justify-between mb-4"<"search-container"f><"length-container"l>>rt<"bottom flex justify-between items-center"<"info-container"i><"pagination-container"p>>',
                ajax: "{{ route('food.categories.get') }}",
                columns: [{
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'px-6 py-4 dk-border-one text-center'
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
                    },
                ],
                order: [
                    [2, 'asc']
                ],
                responsive: true,
                language: {
                    lengthMenu: "_MENU_ entries per page",
                    search: "",
                    searchPlaceholder: "Search categories...",
                    processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
                    emptyTable: 'No food categories found',
                    zeroRecords: 'No matching food categories'
                },
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
                    $('.dataTables_length select').addClass('form-input').removeClass(
                        'form-control form-control-sm');
                    $('.dataTables_filter input').addClass('form-input').removeClass(
                        'form-control form-control-sm');
                },
                createdRow: function(row) {
                    $(row).addClass('bg-white dark:bg-dark-card-two');
                }
            });

            // Column filter binding (wrapper-safe)
            $('#laravel-datatable-foodcategory thead .filters input').on('keyup change', function() {
                const colIndex = $(this).closest('th').index();
                $('#laravel-datatable-foodcategory').DataTable().column(colIndex).search(this.value).draw();
            });

            // Close modal
            $(document).on('click', '#category-modal .close-modal-btn', closeCategoryModal);

            // Form submit
            $('#categoryForm').on('submit', function(e) {
                e.preventDefault();
                const id = $('#category_id').val();
                $.post(`/createupdatefoodcategory/${id}`, $(this).serialize())
                    .done(res => {
                        if (res.success) {
                            closeCategoryModal();
                            table.ajax.reload();
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

            // Edit button logic
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                $.get(`/editfoodcategory/${id}`)
                    .done(res => {
                        openCategoryModal('Edit Food Category');
                        $('#category_id').val(res.id);
                        $('#name').val(res.name);
                    })
                    .fail(() => toastr.error('Failed to load food category details.'));
            });

            // Delete button logic
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This food category will be deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then(result => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/deletefoodcategory/${id}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success(res) {
                                if (res.success) {
                                    table.ajax.reload();
                                    toastr.success(res.success);
                                }
                            },
                            error() {
                                toastr.error('Failed to delete food category.');
                            }
                        });
                    }
                });
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
                .attr('placeholder', 'Search all categories')
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

            // Bind to each column filter
            $('#laravel-datatable-foodcategory thead .filters input').each(function() {
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
