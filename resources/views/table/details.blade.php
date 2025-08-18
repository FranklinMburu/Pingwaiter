@extends('layouts.app1')

@section('content')
    <div class="main-content px-3 xl:px-4">
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-full xl:col-span-8 xl:col-start-3">
                <div class="card p-6 rounded-xl shadow-sm">
                    <div class="flex items-center gap-4">
                        @if (isset($table->restaurant->logo))
                            <img class="w-16 h-16 object-cover rounded-full border"
                                src="{{ url($table->restaurant->logo ? 'uploads/restaurant/logos/' . $table->restaurant->logo : 'uploads/logo.png') }}"
                                alt="Logo">
                        @else
                            <div class="text-xl font-bold text-heading dark:text-white">PingWaiter</div>
                        @endif
                        <div>
                            @if (isset($table->restaurant->name))
                                <h2 class="text-lg font-semibold text-heading dark:text-white">{{ $table->restaurant->name }}
                                </h2>
                            @endif
                            <p class="text-sm text-gray-500 dark:text-dark-text">Table Code:
                                <strong>{{ $table->table_code }}</strong></p>
                        </div>
                    </div>
                </div>

                <div class="card mt-4 p-6 text-center">
                    <h3 class="text-xl font-semibold text-heading dark:text-white mb-2">Customer Menu</h3>
                    <p class="text-sm text-gray-600 dark:text-dark-text">Size: {{ $table->size }}</p>
                    <p class="text-sm text-gray-600 dark:text-dark-text">Location: {{ $table->restaurant->name }}</p>
                    <p class="text-sm text-gray-600 dark:text-dark-text">{{ $table->description }}</p>
                    <p class="text-sm text-danger font-semibold">The link expires in <span id="countdown"></span></p>
                </div>

                <div class="card mt-4 p-6 text-center flex flex-col gap-2">
                    @php $restaurant = $table->restaurant; @endphp

                    @if ($restaurant->allow_place_order)
                        <a href="{{ route('table.menu', ['id' => $table->id, 'token' => request()->token]) }}"
                            class="btn b-solid btn-primary-solid">Place Order</a>
                    @endif

                    @if ($table->status)
                        <button id="callWaiterBtn" onclick="callWaiter({{ $table->id }})"
                            class="btn b-solid btn-success-solid">Ping Waiter</button>
                    @else
                        <button class="btn b-solid btn-success-solid" disabled>Ping is not available...</button>
                    @endif

                    @if ($restaurant->allow_call_manager && $restaurant->manager_whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $restaurant->manager_whatsapp) }}?text=Hello%20Manager%2C%20I%20need%20assistance%20at%20table%20{{ $table->table_code }}"
                            class="btn b-solid btn-warning-solid" target="_blank">Call Manager</a>
                    @endif
                    @if ($restaurant->allow_call_owner)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $restaurant->owner_whatsapp) }}?text=Hello%20Owner%2C%20please%20assist%20at%20table%20{{ $table->table_code }}"
                            class="btn b-solid btn-danger-solid" target="_blank">Call Owner</a>
                    @endif
                    @if ($restaurant->allow_call_supervisor)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $restaurant->supervisor_whatsapp) }}?text=Hello%20Supervisor%2C%20I%20need%20assistance%20at%20table%20{{ $table->table_code }}"
                            class="btn b-solid btn-info-solid" target="_blank">Call Supervisor</a>
                    @endif
                    @if ($restaurant->allow_call_cashier)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $restaurant->cashier_whatsapp) }}?text=Hello%20Cashier%2C%20please%20check%20table%20{{ $table->table_code }}"
                            class="btn b-solid btn-dark-solid" target="_blank">Call Cashier</a>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    @if ($table->restaurant->picture)
                        @php
                            $pictures = is_string($table->restaurant->picture)
                                ? json_decode($table->restaurant->picture, true)
                                : $table->restaurant->picture;
                        @endphp

                        <div class="card p-4">
                            <h5 class="card-title text-center mb-3">Restaurant Pictures</h5>
                            <div class="grid grid-cols-1 gap-4">
                                @foreach ($pictures as $picture)
                                    <a href="{{ asset('uploads/restaurant/pictures/' . $picture) }}"
                                        data-fancybox="restaurant-gallery">
                                        <img src="{{ asset('uploads/restaurant/pictures/' . $picture) }}"
                                            alt="Restaurant Image" class="rounded w-full h-[180px] object-cover">
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($table->restaurant->video)
                        <div class="card p-4">
                            <h5 class="card-title text-center mb-3">Restaurant Video</h5>
                            <video controls class="w-full h-[180px] object-contain">
                                <source src="{{ asset('uploads/restaurant/videos/' . $table->restaurant->video) }}"
                                    type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            Fancybox.bind("[data-fancybox]", {
                loop: true,
                buttons: ["zoom", "slideShow", "fullScreen", "close"],
                animationEffect: "fade"
            });
        });

        function callWaiter(record_id) {
            $.get('/callwaiter/' + record_id, function(data) {
                if (data.success) {
                    alert_toast('Waiter has been called!');
                    $('#callWaiterBtn')
                        .html(
                            `Pinging waiter... <a onclick=\"cancelPing(${data.ping_id})\" class=\"btn btn-sm btn-danger ml-2\">Cancel Ping</a>`
                            );
                } else {
                    alert_toast('An error occurred');
                }
            });
        }

        function cancelPing(pingId) {
            $.get('/endcalling/' + pingId, function(data) {
                if (data.success) {
                    alert_toast('Waiter call cancelled');
                    $('#callWaiterBtn').html('Ping Waiter').prop('disabled', false);
                } else {
                    alert_toast('Failed to cancel call');
                }
            });

            event.stopPropagation();
            return false;
        }
    </script>

    <script>
        const expirationTime = new Date("{{ $accessToken->expires_at }}").getTime();
        const countdownTimer = setInterval(function() {
            const now = new Date().getTime();
            const timeRemaining = expirationTime - now;
            const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);
            const formattedTime = (minutes < 10 ? '0' : '') + minutes + ':' + (seconds < 10 ? '0' : '') + seconds;

            document.getElementById('countdown').textContent = formattedTime;

            if (timeRemaining < 0) {
                clearInterval(countdownTimer);
                document.getElementById('countdown').textContent = 'EXPIRED';
                setTimeout(function() {
                    window.location.href = "{{ route('expired.link') }}";
                }, 3000);
            }
        }, 1000);
    </script>
@endsection
