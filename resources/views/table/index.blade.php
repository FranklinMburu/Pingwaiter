@extends('layouts.app')

@section('title', 'Setup Tables')

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
                    <h3 class="card-title text-3xl font-bold text-heading dark:text-dark-text">Setup Tables</h3>

                    @if (auth()->user()->isRestaurant())
                        <button type="button" onclick="openAddModal()" class="btn btn-primary b-solid btn-primary-solid">
                            <i class="fa fa-plus"></i> Add Table
                        </button>
                    @endif
                </div>
                <div class="p-6 space-y-4">
                    <div id="message" class="w-full mb-4"></div>
                    <div class="overflow-x-auto">
                        <table id="laravel-datatable-table"
                            class="table-auto border-collapse w-full whitespace-nowrap text-left text-gray-500 dark:text-dark-text font-bold">
                            <thead class="bg-[#F2F4F9] dark:bg-dark-card-two">
                                <tr>
                                    <th class="px-6 py-4 dk-border-one w-[1%]">#</th>
                                    <th class="px-6 py-4 dk-border-one">ACTION</th>
                                    <th class="px-6 py-4 dk-border-one">TABLE CODE</th>
                                    <th class="px-6 py-4 dk-border-one">TABLE SIZE</th>
                                    <th class="px-6 py-4 dk-border-one">LOCATION</th>
                                    <th class="px-6 py-4 dk-border-one">DESCRIPTION</th>
                                    <th class="px-6 py-4 dk-border-one">PICTURE</th>
                                    <th class="px-6 py-4 dk-border-one">Print Table QR Code</th>
                                </tr>
                                <tr class="filters">
                                    <th></th>
                                    <th></th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-2 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search code"
                                                class="form-input w-full text-xs !pl-8" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-2 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search size"
                                                class="form-input w-full text-xs !pl-8" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-2 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search location"
                                                class="form-input w-full text-xs !pl-8" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-2 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search description"
                                                class="form-input w-full text-xs !pl-8" />
                                        </div>
                                    </th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-dark-border-three"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BEGIN: Student Add modal  -->
    <div id="inlineForm" class="fixed inset-0 z-modal flex-center modal !hidden bg-black bg-opacity-50 overflow-y-auto">
        <div
            class="modal-content bg-white rounded-lg shadow-lg w-full max-w-3xl transform transition-all duration-300 opacity-0 -translate-y-10 m-4">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-dark-border">
                <h4 class="text-2xl font-bold" id="tablelbl">Add Table Setup</h4>
                <button type="button" class="close text-gray-500 hover:text-gray-700" data-dismiss="modal"
                    aria-label="Close" onclick="closeModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="Register" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="edit_id" id="edit_id">
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-12 gap-x-4">
                        <!-- Table Code -->
                        <div class="col-span-full md:col-span-4">
                            <label class="block font-bold text-gray-700"><strong>Table Code</strong> <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="table_code" id="table_code" class="form-input mt-1 w-full"
                                placeholder="e.g. T001" value="{{ old('table_code') }}">
                            <span class="text-danger" id="table_code-error"></span>
                        </div>
                        <!-- Size -->
                        <div class="col-span-full md:col-span-4">
                            <label class="block font-bold text-gray-700"><strong>Size</strong></label>
                            <input type="number" name="size" id="size" class="form-input mt-1 w-full"
                                placeholder="e.g. 4" value="{{ old('size') }}">
                            <span class="text-danger" id="size-error"></span>
                        </div>
                        <!-- Location -->
                        <div class="col-span-full md:col-span-4">
                            <label class="block font-bold text-gray-700"><strong>Location</strong></label>
                            <input type="text" name="location" id="location" class="form-input mt-1 w-full"
                                placeholder="e.g. Hall A or Outside" value="{{ old('location') }}">
                            <span class="text-danger" id="location-error"></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-x-4">
                        <!-- Picture -->
                        <div class="col-span-full md:col-span-4">
                            <label class="block font-bold text-gray-700"><strong>Picture</strong></label>
                            <input type="file" name="picture" id="picture" class="form-input mt-1 w-full"
                                onchange="previewTableImage(this)">
                            <span class="text-danger" id="picture-error"></span>

                            <!-- Image preview container -->
                            <div id="image-preview-container" class="mt-2 hidden">
                                <img id="table-image-preview" src="#" alt="Table Preview" class="img-thumbnail"
                                    style="max-height: 150px;">
                            </div>

                            <!-- Show existing image when editing -->
                            <div id="existing-image-container" class="mt-2 hidden">
                                <div class="relative">
                                    <img id="existing-table-image" src="#" alt="Current Table Image"
                                        class="img-thumbnail" style="max-height: 150px;">
                                    <button type="button" class="btn btn-sm btn-danger absolute top-1 right-1 p-1"
                                        onclick="removeTableImage()">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                                <input type="hidden" name="remove_picture" id="remove_picture" value="0">
                            </div>
                        </div>
                        <!-- Description -->
                        <div class="col-span-full md:col-span-6">
                            <label class="block font-bold text-gray-700 mt-3"><strong>Description</strong></label>
                            <textarea name="description" id="description" class="form-input h-auto mt-1 w-full resize-none" rows="3"
                                placeholder="Describe the table location">{{ old('description') }}</textarea>
                            <span class="text-danger" id="description-error"></span>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 px-6 py-4 border-t border-gray-200 dark:border-dark-border">
                    <button id="savebutton" type="button" class="btn b-solid btn-primary-solid">Save</button>
                    <button id="closebutton" type="button" class="btn b-light btn-secondary-light"
                        onclick="closeModal()">Close</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openAddModal() {
            $('#tablelbl').html('Add Table Setup');
            $('#table_code').val('');
            $('#picture').val('');
            $('#size').val('');
            $('#description').val('');
            $('#location').val('');
            $('#table_code-error').html('');
            $('#picture-error').html('');
            $('#size-error').html('');
            $('#location-error').html('');
            $('#description-error').html('');
            $('#edit_id').val('');
            $('#savebutton').html('Save');

            // SHOW modal using Tailwind-friendly classes
            $('#inlineForm').removeClass('!hidden').addClass('flex');
            $('.modal-content').removeClass('opacity-0 -translate-y-10').addClass('opacity-100 translate-y-0');
        }

        var errorcolor = "#ffcccc";
        $(function() {
            cardSection = $('#page-block');
        });
        /* BEGIN: Display food menu table using yajra datatable */
        $(document).ready(function() {
            // Initialize Fancybox
            Fancybox.bind("[data-fancybox]", {
                loop: true,
                buttons: [
                    "zoom",
                    "slideShow",
                    "fullScreen",
                    "download",
                    "thumbs",
                    "close"
                ],
                animationEffect: "fade"
            });

            var ftable = $('#laravel-datatable-table').DataTable({
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

                    // Add icon to global search + spacing
                    decorateGlobalSearch();

                    // Recent-search history (per-column)
                    wireSearchHistory();
                },
                ajax: {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/gettables',
                    type: 'DELETE',
                    beforeSend: function() {},
                    complete: function() {},
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: null,
                        render: function(data, type, full, meta) {
                            return `
                                <div class="btn-group">
                                    @if (auth()->user()->isRestaurant())
                                        <button type="button" class="btn btn-sm btn-info mr-2" onclick="tableEditFn(${data.id})" title="Edit table">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="tableDeleteFn(${data.id})" title="Delete table">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            `;
                        },
                        orderable: false,
                        searchable: false
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
                    {
                        data: null,
                        render: function(data) {
                            return `<div class="text-center"><a href="/printqr/${data.id}" class="btn b-solid btn-primary-solid btn-lg dk-theme-card-square">Print QR</a></div>`;
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
            });

            // wrapper-safe (inputs are wrapped with relative div)
            $('#laravel-datatable-table thead .filters input').on('keyup change', function() {
                const colIndex = $(this).closest('th').index();
                ftable.column(colIndex).search(this.value).draw();
            });

        });

        $('#uploadFileInput').on('change', function() {
            const file = this.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('file', file);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: "{{ route('food.categories.import') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success(res) {
                    if (res.success) {
                        toastr.success(res.success);
                        $('#laravel-datatable-table').DataTable().ajax.reload(null, false);

                    }
                },
                error(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Upload failed.');
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
            $('#laravel-datatable-table thead .filters input').each(function() {
                $(this).on('keyup change', function() {
                    const colIndex = $(this).closest('th').index();
                    const val = this.value.trim();
                    if (val) saveSearchHistory(colIndex, val);
                });
                $(this).on('focus', function() {
                    const colIndex = $(this).closest('th').index();
                    showSearchHistory(this, colIndex);
                });
            });
        }
    </script>
    <script>
        $('#savebutton').click(function() {
            var tableForm = $('#Register')[0];
            var formData = new FormData(tableForm);
            var id = $('#edit_id').val() ? $('#edit_id').val() : 'add';

            $.ajax({
                url: '/createupdatetable/' + id,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#savebutton').text(id !== 'add' ? 'Updating...' : 'Saving...').prop("disabled",
                        true);
                    $('.text-danger').text('');
                },
                success: function(data) {
                    if (data.errors) {
                        $.each(data.errors, function(key, value) {
                            $('#' + key + '-error').text(value[0]);
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Input Error',
                            text: 'Check your input and try again.'
                        });
                        $('#savebutton').text(id !== 'add' ? 'Update' : 'Save').prop("disabled", false);
                    } else if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.success
                        });
                        $('#laravel-datatable-table').DataTable().ajax.reload(null, false);

                        $('#savebutton').text('Save').prop("disabled", false);
                        closeModal();
                    }
                }
            });
        });

        function tableEditFn(record_id) {
            $('#edit_id').val(record_id);
            $('#tablelbl').html('Edit Table Setup');
            $('#savebutton').html('Update');
            $.get('/edittable/' + record_id, function(data) {
                if (data.table) {
                    $('#table_code').val(data.table.table_code);
                    $('#size').val(data.table.size);
                    $('#location').val(data.table.location);
                    $('#description').val(data.table.description);

                    // Show existing image if available
                    if (data.table.picture) {
                        var existingImage = document.getElementById('existing-table-image');
                        var existingContainer = document.getElementById('existing-image-container');

                        existingImage.src = '/uploads/table/pictures/' + data.table.picture;
                        existingContainer.style.display = 'block';

                        // Hide the new image preview container
                        document.getElementById('image-preview-container').style.display = 'none';
                    } else {
                        document.getElementById('existing-image-container').style.display = 'none';
                    }
                }
            });
            $('#inlineForm').removeClass('!hidden').addClass('flex');
            $('.modal-content').removeClass('opacity-0 -translate-y-10').addClass('opacity-100 translate-y-0');
        }

        function tableDeleteFn(record_id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to delete this table setup!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteTable(record_id);
                }
            });
        }

        function deleteTable(record_id) {
            $.get('/deletetable/' + record_id, function(data) {
                if (data.success) {
                    alert_toast(data.success, 'success');
                    $('#laravel-datatable-table').DataTable().ajax.reload(null, false);

                } else {
                    alert_toast('An error occured please try again!', 'error');
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
            $('#inlineForm').addClass('!hidden').removeClass('flex');
            $('.modal-content').removeClass('opacity-100 translate-y-0').addClass('opacity-0 -translate-y-10');

            // Optional: reset form fields
            $('#Register')[0].reset();
            $('#edit_id').val('');
            $('.text-danger').html('');
            $('#image-preview-container').hide();
            $('#existing-image-container').hide();
        }

        function previewTableImage(input) {
            var preview = document.getElementById('table-image-preview');
            var previewContainer = document.getElementById('image-preview-container');
            var existingContainer = document.getElementById('existing-image-container');

            // Hide existing image container when a new image is selected
            existingContainer.style.display = 'none';

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.style.display = 'block';
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '#';
                previewContainer.style.display = 'none';
            }
        }

        function removeTableImage() {
            // Set the hidden input value to 1 to indicate image should be removed
            document.getElementById('remove_picture').value = '1';

            // Hide the existing image container
            document.getElementById('existing-image-container').style.display = 'none';

            // Clear the file input
            document.getElementById('picture').value = '';
        }
    </script>
@endpush
