@extends('layouts.app')

@section('title', 'Table Pings')

@section('content')
    <div class="grid grid-cols-12 gap-x-4">
        <div class="col-span-full">
            <div class="card p-0">
                <!-- Header -->
                <div class="flex items-center justify-between px-4 py-5 sm:p-7 bg-gray-200/30 dark:bg-dark-card-two">
                    <h6 class="card-title text-3xl font-bold text-heading dark:text-dark-text">View Table Pings</h6>

                    <button id="toggleSoundBtn" onclick="toggleSound()"
                        class="btn b-light btn-secondary-light text-sm px-4 py-1 rounded">
                        🔈 Mute
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <!-- Flash message -->
                    <div id="message" class="w-full text-sm text-green-600 mb-2"></div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table id="laravel-datatable-request"
                            class="table-auto border-collapse w-full whitespace-nowrap text-left text-gray-600 dark:text-dark-text font-medium">
                            <thead class="bg-[#F2F4F9] dark:bg-dark-card-two">
                                <tr>
                                    <th class="px-6 py-4 border">Action</th>
                                    <th class="px-6 py-4 border">#</th>
                                    <th class="px-6 py-4 border">Time</th>
                                    <th class="px-6 py-4 border">Table Code</th>
                                    <th class="px-6 py-4 border">Table Size</th>
                                    <th class="px-6 py-4 border">Location</th>
                                    <th class="px-6 py-4 border">IP Address</th>
                                    <th class="px-6 py-4 border">Status</th>
                                </tr>
                                <tr class="filters text-xs">
                                    <th></th>
                                    <th></th>
                                    <th class="px-6 py-2 border">
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search time"
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                    <th class="px-6 py-2 border">
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search table"
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                    <th class="px-6 py-2 border">
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search size"
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                    <th class="px-6 py-2 border">
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search location"
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                    <th class="px-6 py-2 border">
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search IP"
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                    <th class="px-6 py-2 border">
                                        <div class="relative">
                                            <i
                                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                                            <input type="text" placeholder="Search status"
                                                class="form-input w-full !pl-8 bg-white placeholder-gray-400" />
                                        </div>
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 dark:divide-dark-border-three"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BEGIN: Sound Modal -->
    <div id="soundModal"
        class="fixed inset-0 z-modal hidden items-center justify-center bg-black bg-opacity-50 overflow-y-auto">
        <div
            class="modal-content bg-white rounded-lg shadow-lg w-full max-w-lg transform transition-all duration-300 m-4 p-6">
            <div class="flex items-center justify-between border-b border-gray-200 pb-3 mb-4">
                <h4 class="text-lg font-semibold text-heading">Enable Table Ping Sounds</h4>
                <button type="button" onclick="closeSoundModal()" class="text-gray-500 hover:text-gray-700 text-xl">
                    &times;
                </button>
            </div>
            <div class="text-sm text-gray-600 mb-4">
                Click "Enable" to hear sound notifications when tables request service.
            </div>
            <div class="flex justify-end gap-3">
                <button onclick="closeSoundModal()" class="btn b-light btn-secondary-light">Cancel</button>
                <button onclick="enableSound()" class="btn b-solid btn-primary-solid">Enable</button>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    {{-- Bootstrap Icons for the search icon --}}
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
@endpush

@push('scripts')
    @vite(['resources/js/echo.js'])
    <script>
        const auth_user_role = "{{ auth()->user()->role }}";
        let audioEnabled = false;

        $(document).ready(function() {
            let table = $('#laravel-datatable-request').DataTable({
                processing: true,
                // serverSide: true,
                deferRender: true,
                scrollY: 400,
                scroller: true,
                orderCellsTop: true,
                fixedHeader: true,
                dom: '<"top flex justify-start mb-2"f>rt<"bottom mt-4"lip>',
                ajax: "{{ route('get.table.pings') }}",
                columns: [{
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'time_elapsed',
                        name: 'time_elapsed'
                    },
                    {
                        data: 'table_code',
                        name: 'table_code'
                    },
                    {
                        data: 'table.size',
                        name: 'table.size'
                    },
                    {
                        data: 'location',
                        name: 'location'
                    },
                    {
                        data: 'ip_address',
                        name: 'ip_address'
                    },
                    {
                        data: null,
                        render: function(data) {
                            if (data.is_attended) return 'ATTENDED';
                            if (data.seen === 0) {
                                return `<button onclick="endCalling(${data.id})"
                                    class="btn bg-black text-white px-4 py-1 rounded shadow">CALLING</button>`;
                            }
                            return 'CANCELLED';
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                fnRowCallback: function(nRow, aData) {
                    if (aData.seen === 0) {
                        const message = `Table ${aData.table_code} is calling for a waiter.`;
                        const utterance = new SpeechSynthesisUtterance(message);
                        speechSynthesis.speak(utterance);
                    }
                },
                order: [
                    [3, 'desc']
                ],
                responsive: true,
                language: {
                    search: '',
                    searchPlaceholder: "Search...",
                    processing: '<i class="fa fa-spinner fa-spin fa-2x"></i>',
                    emptyTable: 'No table pings found',
                    zeroRecords: 'No matching table pings'
                },
                initComplete: function() {
                    const api = this.api();

                    // Make column filters work (unchanged)
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
            });

            // Optional manual binding (kept as in your code)
            $('#laravel-datatable-request thead .filters input').on('keyup change', function() {
                const colIndex = $(this).parent().index();
                $('#laravel-datatable-request').DataTable().column(colIndex).search(this.value).draw();
            });
        });

        // === Global search: add icon and input spacing ===
        function decorateGlobalSearch() {
            const $filter = $('.dataTables_filter');
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

            // Give the label a fixed width so the wrapper shows nicely
            $filter.find('label').addClass('block w-64');
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

                // Clean any existing dropdown
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

        // ===== Your existing functions (unchanged) =====
        function endCalling(id) {
            $.get(`/endcalling/${id}`, function(response) {
                alert_toast(response.success ? 'Calling ended!' : 'An error occurred');
                $('#laravel-datatable-request').DataTable().ajax.reload();
            });
        }

        function checkNewPings() {
            $.get("{{ route('get.table.pings') }}", function(data) {
                const newCalls = data.data.filter(p => p.seen === 0);
                if (newCalls.length > 0 && audioEnabled) playNotificationSound();
            });
        }

        function playNotificationSound() {
            const audio = new Audio('/assets/audio/service-bell.mp3');
            audio.play().catch(e => console.error('Sound failed:', e));
        }

        function showSoundModal() {
            document.getElementById('soundModal').classList.remove('hidden');
        }

        function closeSoundModal() {
            document.getElementById('soundModal').classList.add('hidden');
        }

        function enableSound() {
            audioEnabled = true;
            const audio = new Audio('/assets/audio/service-bell.mp3');
            audio.volume = 0.2;
            audio.play().catch(console.error);
            closeSoundModal();
        }

        function toggleSound() {
            audioEnabled = !audioEnabled;
            const btn = document.getElementById('toggleSoundBtn');
            btn.innerText = audioEnabled ? '🔊 Unmute' : '🔈 Mute';
            if (audioEnabled) {
                const audio = new Audio('/assets/audio/service-bell.mp3');
                audio.volume = 0.2;
                audio.play().catch(console.error);
            }
        }

        function toggleBanCustomer(pingId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to toggle this customer's ban status.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Yes, proceed!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/table/pings/${pingId}/toggle/ban`,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire('Success', response.success, 'success');
                            $('#laravel-datatable-request').DataTable().ajax.reload();
                        },
                        error: function() {
                            Swal.fire('Error', 'Something went wrong.', 'error');
                        }
                    });
                }
            });
        }

        function markAsAttended(pingId) {
            Swal.fire({
                title: 'Confirm',
                text: 'Mark this table ping as attended?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, mark as attended',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/table/pings/${pingId}/mark/attended`,
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire('Success', response.success || 'Ping marked as attended.',
                                'success');
                            $('#laravel-datatable-request').DataTable().ajax.reload();
                        },
                        error: function() {
                            Swal.fire('Error', 'Something went wrong.', 'error');
                        }
                    });
                }
            });
        }

        // Show sound opt-in once and polling
        showSoundModal();
        setInterval(checkNewPings, 1000);
    </script>
@endpush
