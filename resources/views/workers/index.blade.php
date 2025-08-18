@extends('layouts.app')

@section('title', 'Invite Workers')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
    {{-- Bootstrap Icons for search icon --}}
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" />
@endpush

@section('content')
    <div class="grid grid-cols-12 gap-x-4">
        <div class="col-span-full">
            <div class="card p-0">
                <!-- Header -->
                <div
                    class="flex flex-col gap-2 sm:flex-row sm:justify-between px-4 py-5 sm:p-7 bg-gray-200/30 dark:bg-dark-card-two">
                    <h3 class="card-title text-3xl font-bold text-heading dark:text-dark-text">Invite Workers</h3>
                    <!-- Invite Worker button -->
                    <div class="flex justify-end">
                        <button type="button" onclick="openInviteModal()"
                            class="btn b-solid btn-primary-solid dk-theme-card-square">
                            <i class="ri-add-fill text-white text-[18px] mr-1"></i> Invite Worker
                        </button>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    <!-- Flash / AJAX messages -->
                    <div id="message" class="text-sm text-green-600"></div>

                    <!-- Table wrapper -->
                    <div class="overflow-x-auto">
                        <table id="laravel-datatable-worker"
                            class="table-auto border-collapse w-full whitespace-nowrap text-left text-gray-500 dark:text-dark-text font-medium">
                            <thead class="bg-[#F2F4F9] dark:bg-dark-card-two">
                                <tr>
                                    <th class="p-6 py-4 dk-border-one w-[10%] text-center">Actions</th>
                                    <th class="p-6 py-4 dk-border-one w-[5%]">#</th>
                                    <th class="p-6 py-4 dk-border-one w-[25%]">Name</th>
                                    <th class="p-6 py-4 dk-border-one w-[30%]">Email</th>
                                    <th class="p-6 py-4 dk-border-one w-[30%]">Designation</th>
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

    @include('workers.modals.invite-worker')
@endsection

@push('scripts')
    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Modal open/close
            window.openInviteModal = function() {
                $('#Register')[0].reset();
                $('.text-danger').addClass('hidden').text('');
                $('#invite-modal h2').text('Invite Worker');
                const $m = $('#invite-modal');
                $m.removeClass('!hidden');
                setTimeout(() => {
                    $m.find('.modal-content').removeClass('opacity-0 -translate-y-10');
                }, 10);
            };

            function closeInviteModal() {
                const $m = $('#invite-modal');
                $m.find('.modal-content').addClass('opacity-0 -translate-y-10');
                setTimeout(() => {
                    $m.addClass('!hidden');
                }, 300);
            }
            // Attach close handler
            $(document).on('click', '#invite-modal .close-modal-btn', closeInviteModal);

            // Initialize Select2
            $('.select2-designation').select2({
                placeholder: 'Select Designation',
                allowClear: false,
                width: '100%',
                theme: 'classic',
                dropdownParent: $('#invite-modal')
            });

            // Init DataTable
            const table = $('#laravel-datatable-worker').DataTable({
                destroy: true,
                processing: true,
                ajax: '/getworkers',
                deferRender: true,
                scrollY: 400,
                scroller: true,
                orderCellsTop: true,
                fixedHeader: true,
                dom: '<"flex items-center justify-between mb-4 gap-4"<"search-container"f><"length-container"l>>rt<"bottom flex justify-between items-center"<"info-container"i><"pagination-container"p>>',
                columns: [{
                        data: 'id',
                        name: 'id',
                        orderable: false,
                        searchable: false,
                        className: 'px-6 py-4 dk-border-one text-center',
                        render: function(data, type, row) {
                            return `
                                <div class="flex justify-center gap-2">
                                    <button class="edit-btn text-blue-600 hover:text-blue-800" data-id="${row.id}" title="Edit">
                                        <i class="ri-edit-line text-lg"></i>
                                    </button>
                                    <button class="delete-btn text-red-600 hover:text-red-800" data-id="${row.id}" title="Delete">
                                        <i class="ri-delete-bin-line text-lg"></i>
                                    </button>
                                </div>
                            `;
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
                    },
                    {
                        data: 'email',
                        name: 'email',
                        className: 'px-6 py-4 dk-border-one'
                    },
                    {
                        data: 'designation',
                        name: 'designation',
                        className: 'px-6 py-4 dk-border-one'
                    }
                ],
                language: {
                    search: '',
                    searchPlaceholder: 'Search workers...'
                },
                initComplete: function() {
                    const api = this.api();

                    // (No column filters here — keeping your markup the same.)

                    // 1) Decorate the GLOBAL search input with a Bootstrap icon
                    decorateGlobalSearch();

                    // 2) Recent-search history for GLOBAL search (Tailwind dropdown)
                    wireGlobalSearchHistory(api);
                },
            });

            // Submit invite
            $('#savebutton').on('click', function() {
                const data = $('#Register').serialize();
                $.ajax({
                    url: '/inviteworker',
                    method: 'POST',
                    data: data,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    success: function(res) {
                        if (res.errors) {
                            $.each(res.errors, (k, v) => {
                                $(`#${k}-error`).removeClass('hidden').text(v[0]);
                            });
                        } else if (res.email_duplication) {
                            Swal.fire('Error', res.email_duplication, 'error');
                        } else {
                            Swal.fire('Success', res.success, 'success');
                            table.ajax.reload(null, false);
                            // close via the same function as the button in the modal
                            $('#invite-modal .close-modal-btn').trigger('click');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Something went wrong while submitting the form.',
                            'error');
                    }
                });
            });

            // DELETE handler
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                window.confirmDeleteWorker(id);
            });

            // EDIT handler
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                $.get(`/editworker/${id}`)
                    .done(res => {
                        if (res && res.worker) {
                            openInviteModal();
                            $('#invite-modal h2').text('Edit Worker');
                            $('#Register input[name="id"]').val(res.worker.id);
                            $('#Register input[name="name"]').val(res.worker.name);
                            $('#Register input[name="email"]').val(res.worker.email);
                            $('#Register select[name="designation_id"]').val(res.worker.designation_id)
                                .trigger('change');
                        } else {
                            Swal.fire('Error', 'Worker data missing.', 'error');
                        }
                    })
                    .fail(() => {
                        Swal.fire('Error', 'Failed to fetch worker data.', 'error');
                    });
            });

            // SweetAlert2 delete
            window.confirmDeleteWorker = function(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This worker will be deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((res) => {
                    if (res.isConfirmed) {
                        $.ajax({
                            url: `/deleteworker/${id}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            }
                        }).done(resp => {
                            if (resp.success) {
                                Swal.fire('Deleted!', resp.success, 'success');
                                table.ajax.reload(null, false);
                            } else {
                                Swal.fire('Error', resp.error || 'Try again', 'error');
                            }
                        });
                    }
                });
            };

            // Expose for action buttons
            window.deleteWorker = confirmDeleteWorker;
        });

        // === Global search: add icon and proper padding ===
        function decorateGlobalSearch() {
            const $filter = $('.search-container .dataTables_filter');
            if (!$filter.length) return;

            // Remove the "Search:" label text node
            $filter.find('label').contents().filter(function() {
                return this.nodeType === 3;
            }).remove();

            const $input = $filter.find('input');
            $input
                .addClass('form-input w-full !pl-8 bg-white placeholder-gray-400')
                .attr('placeholder', 'Search workers...')
                .wrap('<div class="relative"></div>')
                .parent()
                .prepend(
                    '<i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>'
                );

            // Optional fixed width to keep toolbar tidy
            $filter.find('label').addClass('block w-64');
        }

        // === Recent-search history for GLOBAL search (Tailwind dropdown) ===
        function wireGlobalSearchHistory(api) {
            const key = 'searchHistory_workers_global';
            const maxHistory = 5;

            const $input = $('.search-container .dataTables_filter input');
            if (!$input.length) return;

            function loadHistory() {
                return JSON.parse(localStorage.getItem(key) || '[]');
            }

            function saveTerm(term) {
                if (!term) return;
                let history = loadHistory();
                history = history.filter(v => v !== term);
                history.unshift(term);
                history = history.slice(0, maxHistory);
                localStorage.setItem(key, JSON.stringify(history));
            }

            function showDropdown() {
                const history = loadHistory();
                if (!history.length) return;

                // remove existing
                $input.siblings('.search-history-dd').remove();

                const dd = document.createElement('div');
                dd.className =
                    'search-history-dd absolute z-50 bg-white border border-gray-200 rounded shadow-lg w-full mt-1';
                dd.innerHTML = history.map(item =>
                    `<div class="px-3 py-1 hover:bg-gray-100 cursor-pointer">${item}</div>`
                ).join('');

                dd.addEventListener('click', (e) => {
                    if (e.target && e.target.matches('div')) {
                        $input.val(e.target.textContent).trigger('input');
                        api.search(e.target.textContent).draw();
                        dd.remove();
                    }
                });

                $input.parent()[0].appendChild(dd);

                // close on outside click
                document.addEventListener('click', function handler(ev) {
                    if (!dd.contains(ev.target)) {
                        dd.remove();
                        document.removeEventListener('click', handler);
                    }
                });
            }

            // Save on manual search (keyup Enter or blur/change)
            let saveTimer = null;
            $input.on('keyup change', function() {
                const val = this.value.trim();
                clearTimeout(saveTimer);
                // debounce save just a tad so we don't spam
                saveTimer = setTimeout(() => saveTerm(val), 300);
            });

            // Show on focus
            $input.on('focus', showDropdown);
        }
    </script>
@endpush
