@extends('layouts.customer')

@section('title', 'Pingwaiter - Restaurant Management Platform')

@push('styles')
    <style>
        .ping-status {
            position: fixed;
            bottom: 100px;
            left: 50%;
            transform: translateX(-50%);
            background: #10b981;
            color: white;
            padding: 16px 24px;
            border-radius: 25px;
            display: none;
            align-items: center;
            gap: 12px;
            z-index: 1000;
        }

        .ping-status.show {
            display: flex;
        }

        .video-container iframe {
            width: 100%;
            max-width: 640px;
            height: 360px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            text-align: center;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn-info {
            background-color: #3b82f6;
            color: white;
        }

        .btn-info:hover {
            background-color: #2563eb;
            transform: translateY(-1px);
        }
    </style>
@endpush

@section('content')
    @if(isset($customer) && $customer->isBanned())
        <x-ban-notice :banReason="$customer->ban_reason" :contact="config('app.contact_email', 'support@example.com')" />
    @endif
    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Welcome Card -->
        <div class="content-card text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Table {{ $tableCode }}</h2>
            {{-- <p class="text-gray-600">Use the floating menu to place orders and get assistance from our staff.</p> --}}
        </div>

        <!-- Quick Actions Grid -->
        <div class="content-card mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @if ($restaurant['allow_place_order'] && (!isset($customer) || !$customer->isBanned()))
                    <!-- Place Order -->
                    <a href="{{ route('order.create', ['restaurant' => $restaurant['id'], 'table' => $tableCode]) }}"
                        class="flex flex-col items-center p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl hover:from-blue-100 hover:to-indigo-100 transition-all">
                        <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center mb-2">
                            <i class="bi bi-plus-circle-fill text-white text-2xl"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Place Order</span>
                    </a>
                @endif

                <!-- Call Waiter -->
                <button onclick="pingWaiter()"
                    class="flex flex-col items-center p-4 bg-gradient-to-br from-purple-50 to-violet-50 rounded-xl hover:from-purple-100 hover:to-violet-100 transition-all">
                    <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center mb-2">
                        <i class="bi bi-bell-fill text-white text-2xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Call Waiter</span>
                </button>

                <!-- Order History -->
                <a href="{{ route('customer.orders.history', ['table' => $tableCode]) }}"
                    class="flex flex-col items-center p-4 bg-gradient-to-br from-orange-50 to-red-50 rounded-xl hover:from-orange-100 hover:to-red-100 transition-all">
                    <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center mb-2">
                        <i class="bi bi-clock-history text-white text-2xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Order History</span>
                </a>

                <!-- Contact Support -->
                <a href="{{ route('customer.contact', ['table' => $tableCode]) }}"
                    class="flex flex-col items-center p-4 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl hover:from-green-100 hover:to-emerald-100 transition-all">
                    <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center mb-2">
                        <i class="bi bi-chat-dots-fill text-white text-2xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Contact Support</span>
                </a>
            </div>
        </div>

        <!-- Order Status Summary in Header -->
        @if($orders->count())
            <div class="content-card mb-4 flex flex-col md:flex-row items-center justify-between bg-blue-50">
                <div class="flex flex-col md:flex-row items-center gap-2">
                    <span class="font-bold text-blue-700">Active Orders:</span>
                    @foreach($orders as $order)
                        <span class="badge px-2 py-1 rounded {{ $order->status == 'pending' ? 'bg-yellow-200 text-yellow-800' : ($order->status == 'confirmed' ? 'bg-blue-200 text-blue-800' : ($order->status == 'preparing' ? 'bg-orange-200 text-orange-800' : ($order->status == 'ready' ? 'bg-green-200 text-green-800' : ($order->status == 'delivered' ? 'bg-purple-200 text-purple-800' : ($order->status == 'paid' ? 'bg-gray-200 text-gray-800' : 'bg-red-200 text-red-800'))))) }}">
                            #{{ $order->order_number }}: {{ ucfirst($order->status) }}
                        </span>
                        @if($order->hasNewStatus)
                            <span class="inline-block ml-1 bg-red-500 text-white text-xs px-2 py-1 rounded-full animate-pulse">New</span>
                        @endif
                    @endforeach
                </div>
                <div class="flex gap-2 mt-2 md:mt-0">
                    @foreach($orders as $order)
                        <a href="{{ route('customer.orders.status', ['order' => $order->id, 'table' => $tableCode]) }}" class="btn btn-info px-3 py-1 rounded text-xs">View Order Status</a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Recent Orders (multiple order handling) -->
        <div class="content-card mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Orders</h3>
            @if ($orders->count())
                <div class="space-y-4">
                    @foreach ($orders as $order)
                        <a href="{{ route('customer.orders.status', ['order' => $order->id, 'table' => $tableCode]) }}"
                            class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all">
                            <img src="/uploads/food/pictures/{{ $order->item->picture[0] }}" alt="Food Image"
                                class="w-16 h-16 object-cover rounded-lg shadow mr-4">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-800">Order #{{ $order->id }}</h4>
                                <p class="text-sm text-gray-600">
                                    {{ $order->item->name }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $order->created_at->format('h:i A') }} — Status:
                                    <span class="font-semibold">{{ $order->status->label() }}</span>
                                    @if($order->hasNewStatus)
                                        <span class="inline-block ml-1 bg-red-500 text-white text-xs px-2 py-1 rounded-full animate-pulse">New</span>
                                    @endif
                                </p>
                            </div>
                            <i class="bi bi-chevron-right text-gray-400 text-xl"></i>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-50 text-center py-8 rounded-lg">
                    <i class="bi bi-receipt text-4xl text-gray-300 mb-3"></i>
                    <h4 class="text-gray-600 font-medium">No orders placed yet</h4>
                    <p class="text-sm text-gray-500">Start by placing your first order from the menu.</p>
                </div>
            @endif
        </div>

        @if (!empty($restaurant['opening_hours']))
            <div class="content-card mb-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Opening Hours</h3>
                <div class="w-full text-sm text-gray-700">
                    <div class="flex justify-between bg-gray-50 px-4 py-2 rounded-md shadow-sm">
                        <span class="font-medium uppercase">{{ $restaurant['opening_hours'] }}</span>
                    </div>
                </div>
            </div>
        @endif


        <!-- Restaurant Gallery -->
        <div class="content-card">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Gallery</h3>
            <div class="gallery-grid">
                @foreach ($restaurant['photos'] as $photo)
                    <div class="gallery-item">
                        <img src="{{ $photo }}" alt="Restaurant Photo"
                            class="w-full h-48 object-cover rounded-lg shadow-md">
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Restaurant Video -->
        <div class="content-card">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Experience Our Restaurant</h3>
            <div class="video-container flex justify-center">
                @if ($restaurant['video_url'])
                    <iframe class="w-full max-w-3xl h-64 rounded-lg shadow-lg"
                        src="{{ app(\App\Services\YouTubeService::class)->getEmbedUrl($restaurant['video_url']) }}"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
                @else
                    <div
                        class="w-full h-64 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center rounded-lg">
                        <p class="text-sm text-gray-500">No restaurant video available</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Find Us Online -->
        @if (
            !empty($restaurant['google_maps_link']) ||
                !empty($restaurant['google_review_link']) ||
                !empty($restaurant['instagram_link']) ||
                !empty($restaurant['facebook_link']) ||
                !empty($restaurant['twitter_link']))
            <div class="content-card mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Find Us Online</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @if (!empty($restaurant['google_maps_link']))
                        <a href="{{ $restaurant['google_maps_link'] }}" target="_blank"
                            class="flex items-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl hover:shadow transition-all">
                            <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center mr-3">
                                <i class="bi bi-geo-alt text-white text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-800">View on Google Maps</span>
                        </a>
                    @endif

                    @if (!empty($restaurant['google_review_link']))
                        <a href="{{ $restaurant['google_review_link'] }}" target="_blank"
                            class="flex items-center p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl hover:shadow transition-all">
                            <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center mr-3">
                                <i class="bi bi-star text-white text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-800">Leave a Google Review</span>
                        </a>
                    @endif

                    @if (!empty($restaurant['instagram_link']))
                        <a href="{{ $restaurant['instagram_link'] }}" target="_blank"
                            class="flex items-center p-4 bg-gradient-to-br from-pink-50 to-pink-100 rounded-xl hover:shadow transition-all">
                            <div class="w-12 h-12 bg-pink-500 rounded-xl flex items-center justify-center mr-3">
                                <i class="bi bi-instagram text-white text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-800">Follow on Instagram</span>
                        </a>
                    @endif

                    @if (!empty($restaurant['facebook_link']))
                        <a href="{{ $restaurant['facebook_link'] }}" target="_blank"
                            class="flex items-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl hover:shadow transition-all">
                            <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mr-3">
                                <i class="bi bi-facebook text-white text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-800">Follow on Facebook</span>
                        </a>
                    @endif

                    @if (!empty($restaurant['twitter_link']))
                        <a href="{{ $restaurant['twitter_link'] }}" target="_blank"
                            class="flex items-center p-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl hover:shadow transition-all">
                            <div class="w-12 h-12 bg-gray-800 rounded-xl flex items-center justify-center mr-3">
                                <i class="bi bi-twitter-x text-white text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-800">Follow on Twitter</span>
                        </a>
                    @endif
                </div>
            </div>
        @endif

    </main>

    <!-- Floating Menu -->
    <div class="floating-menu z-50">
        {{-- Place Order --}}
        <a href="{{ route('order.create', ['restaurant' => $restaurant['id'], 'table' => $tableCode]) }}"
            class="floating-btn" title="Place Order">
            <div class="menu-tooltip">Place Order</div>
            <i class="bi bi-plus-circle text-3xl"></i>
        </a>

        {{-- Ping Waiter --}}
        <button type="button" class="floating-btn" title="Ping Waiter" id="pingBtn" onclick="pingWaiter()">
            <div class="menu-tooltip">Ping Waiter</div>
            <i class="bi bi-bell-fill text-3xl"></i>
        </button>

        {{-- Order History --}}
        <a href="{{ route('customer.orders.history', ['table' => $tableCode]) }}" class="floating-btn"
            title="Order History">
            <div class="menu-tooltip">Order History</div>
            <i class="bi bi-clock-history text-3xl"></i>
        </a>

        {{-- Manager --}}
        @if (!empty($restaurant['manager_whatsapp']) && $restaurant['allow_call_manager'])
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $restaurant['manager_whatsapp']) }}"
                class="floating-btn" title="WhatsApp Manager" target="_blank">
                <div class="menu-tooltip">Manager</div>
                <i class="bi bi-whatsapp text-success text-3xl"></i>
            </a>
        @endif

        {{-- Owner --}}
        @if (!empty($restaurant['owner_whatsapp']) && $restaurant['allow_call_owner'])
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $restaurant['owner_whatsapp']) }}"
                class="floating-btn" title="WhatsApp Owner" target="_blank">
                <div class="menu-tooltip">Owner</div>
                <i class="bi bi-whatsapp text-success text-3xl"></i>
            </a>
        @endif

        {{-- Cashier --}}
        @if (!empty($restaurant['cashier_whatsapp']) && $restaurant['allow_call_cashier'])
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $restaurant['cashier_whatsapp']) }}"
                class="floating-btn" title="WhatsApp Cashier" target="_blank">
                <div class="menu-tooltip">Cashier</div>
                <i class="bi bi-whatsapp text-success text-3xl"></i>
            </a>
        @endif

        {{-- Supervisor --}}
        @if (!empty($restaurant['supervisor_whatsapp']) && $restaurant['allow_call_supervisor'])
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $restaurant['supervisor_whatsapp']) }}"
                class="floating-btn" title="WhatsApp Supervisor" target="_blank">
                <div class="menu-tooltip">Supervisor</div>
                <i class="bi bi-whatsapp text-success text-3xl"></i>
            </a>
        @endif
    </div>

    <!-- Ping Status -->
    <div class="ping-status" id="pingStatus">
        <div class="flex items-center">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <span>Pinging waiter…</span>
        </div>
        <button onclick="cancelPing()" class="ml-4 text-white hover:text-gray-200 font-medium">
            Cancel
        </button>
    </div>
@endsection

@push('scripts')
    <script>
        let isPinging = false;

        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('hidden');
            const arrow = document.getElementById('profileArrow');
            arrow.classList.toggle('rotate-180');
        }

        function pingWaiter() {
            if (!isPinging) {
                isPinging = true;
                document.getElementById('pingBtn').classList.add('active');
                document.getElementById('pingStatus').classList.add('show');

                fetch("{{ route('table.ping', ['table' => $tableCode]) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                });

                setTimeout(() => {
                    if (isPinging) {
                        cancelPing();

                        const audio = new Audio('/assets/audio/service-bell.mp3');
                        audio.volume = 0.1;
                        audio.play().catch(e => console.error('Sound failed:', e));

                        Swal.fire({
                            title: 'Ping Sent!',
                            text: 'Waiter is on the way to your table!',
                            icon: 'success',
                            confirmButtonText: 'Okay',
                            timer: 5000
                        });
                    }
                }, 5000);
            }
        }

        function cancelPing() {
            isPinging = false;
            document.getElementById('pingBtn').classList.remove('active');
            document.getElementById('pingStatus').classList.remove('show');
        }
    </script>
@endpush

@php
    $isAcceptingOrders = $restaurant['is_accepting_orders'] ?? true;
    $closedMessage = $restaurant['closed_message'] ?? 'Restaurant is currently closed.';
@endphp

<div x-data="{ accepting: {{ $isAcceptingOrders ? 'true' : 'false' }}, closedMessage: '{{ $closedMessage }}', loading: false, error: false }"
     x-init="setInterval(() => { loading = true; error = false; fetch('/api/restaurant/status').then(r => r.json()).then data => { accepting = data.is_accepting_orders; closedMessage = data.closed_message; loading = false; }).catch(() => { error = true; loading = false; }); }, 30000)">
    <template x-if="loading">
        <div class="flex flex-col items-center animate-pulse">
            <button class="floating-btn bg-gray-300 text-gray-600 font-bold border-2 border-gray-400 cursor-wait" aria-label="Checking restaurant status..." disabled>
                <div class="menu-tooltip">Checking status...</div>
                <svg class="animate-spin h-6 w-6 text-gray-600" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </button>
        </div>
    </template>
    <template x-if="error">
        <div class="flex flex-col items-center">
            <button class="floating-btn bg-red-500 text-white font-bold border-2 border-red-700 animate-shake" aria-label="Status check failed" disabled>
                <div class="menu-tooltip">Status check failed</div>
                <i class="bi bi-exclamation-triangle text-3xl"></i>
            </button>
            <div class="mt-2 text-red-600 font-semibold text-center">Unable to check restaurant status. Please try again later.</div>
        </div>
    </template>
    <template x-if="!loading && !error && accepting">
        <a href="{{ route('order.create', ['restaurant' => $restaurant['id'], 'table' => $tableCode]) }}"
            class="floating-btn bg-green-500 hover:bg-green-600 text-white font-bold border-2 border-green-700 transition-all duration-300" title="Place Order" aria-label="Place Order">
            <div class="menu-tooltip">Place Order</div>
            <i class="bi bi-plus-circle text-3xl"></i>
        </a>
    </template>
    <template x-if="!loading && !error && !accepting">
        <div class="flex flex-col items-center animate-fade-in">
            <a href="#menu"
                class="floating-btn bg-gray-400 hover:bg-gray-500 text-white font-bold border-2 border-gray-700 transition-all duration-300" title="View Menu" aria-label="View Menu">
                <div class="menu-tooltip">View Menu</div>
                <i class="bi bi-eye text-3xl"></i>
            </a>
            <div class="mt-2 text-red-600 font-semibold text-center" x-text="closedMessage"></div>
        </div>
    </template>
</div>
