@php
    $tableCode = request()->route('table');
    $table = \App\Models\Table::where('table_code', $tableCode)->first();
    $restaurant = $table ? $table->restaurant : null;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <link rel="icon" type="image/ico" href="{{ URL::asset('favicon.ico') }}" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts - Playfair Display for headings and Lato for body text -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Lato:wght@300;400;700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- jQuery Validation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    @vite(['resources/css/app.css'])

    <link rel="stylesheet" href="{{ URL::asset('assets/css/auth.css') }}">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'playfair': ['"Playfair Display"', 'serif'],
                        'lato': ['Lato', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .floating-menu {
            position: fixed;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .floating-btn {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: white;
            color: #8065ee;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            backdrop-filter: blur(10px);
        }

        .floating-btn:hover {
            background: #8065ee;
            color: white;
            transform: scale(1.05);
        }

        .floating-btn.active {
            background: #10b981;
            color: white;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.8;
            }
        }

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

        .table-code {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .menu-tooltip {
            position: absolute;
            right: 65px;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .floating-btn:hover .menu-tooltip {
            opacity: 1;
        }

        .content-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
        }

        .gallery-item {
            border-radius: 16px;
            overflow: hidden;
            background: #f8fafc;
        }

        .video-container {
            border-radius: 16px;
            overflow: hidden;
            background: #f8fafc;
        }

        @media (max-width: 768px) {
            .floating-menu {
                right: 15px;
                gap: 12px;
            }

            .floating-btn {
                width: 48px;
                height: 48px;
                border-radius: 12px;
            }

            .content-card {
                padding: 20px;
                margin-bottom: 20px;
                border-radius: 16px;
            }

            .gallery-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 12px;
            }
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 min-h-screen font-lato">

    <!-- Header -->
    <header
        class="sticky top-0 z-50 backdrop-filter backdrop-blur-lg bg-white bg-opacity-10 border-b border-white border-opacity-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">

                    @if (!request()->routeIs('table.menu'))
                        <!-- Back Button -->
                        <a href="{{ URL::temporarySignedRoute('table.menu', now()->addMinutes(20), ['table' => $tableCode]) }}"
                            class="flex items-center space-x-2 text-white hover:text-purple-300 transition-colors">
                            <i class="bi bi-arrow-left text-lg"></i>
                        </a>
                    @endif

                    <!-- Restaurant Logo -->
                    @if ($restaurant->logo)
                        <div
                            class="h-12 w-auto bg-white/20 backdrop-filter backdrop-blur-lg rounded-xl flex items-center justify-center border border-white border-opacity-30">
                            <img src="{{ URL::asset('/uploads/restaurant/logos/' . $restaurant->logo) }}"
                                alt="Restaurant Logo" class="w-auto h-12 rounded-lg">
                        </div>
                    @endif
                    <!-- Restaurant Name -->
                    <h3 class="text-xl font-bold text-white">
                        {{ $restaurant['name'] }}
                    </h3>
                </div>

                <!-- Table Code -->
                {{-- <div class="table-code">
                    {{ $tableCode }}
                </div> --}}

                <div class="relative">
                    <button onclick="toggleProfileDropdown()"
                        class="flex items-center space-x-2 bg-white/20 backdrop-filter backdrop-blur-lg rounded-xl px-3 py-2 border border-white border-opacity-30 hover:bg-opacity-30 transition-all">
                        <div
                            class="w-8 h-8 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                            <img src="{{ auth()->user()->getFirstMediaUrl('avatar') ?: asset('assets/images/user/profile-img.png') }}"
                                alt="{{ auth()->user()->name }}"
                                class="size-7 sm:size-9 rounded-50 dk-theme-card-square">
                        </div>
                        <span class="text-white font-medium hidden sm:block">{{ auth()->user()->name }}</span>
                        <svg width="16" height="16" fill="none" stroke="white" viewBox="0 0 24 24"
                            class="transition-transform" id="profileArrow">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="profileDropdown"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 py-2 hidden z-50">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                        </div>

                        <div class="border-t border-gray-100 mt-2 pt-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 hover:bg-red-50 flex items-center space-x-2 transition-colors text-red-600">
                                    <svg width="16" height="16" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>


    @yield('content')

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function goBack() {
            window.history.back();
        }
        let isProfileDropdownOpen = false;

        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            const arrow = document.getElementById('profileArrow');

            isProfileDropdownOpen = !isProfileDropdownOpen;

            if (isProfileDropdownOpen) {
                dropdown.classList.remove('hidden');
                arrow.style.transform = 'rotate(180deg)';
            } else {
                dropdown.classList.add('hidden');
                arrow.style.transform = 'rotate(0deg)';
            }
        }
    </script>

    @stack('scripts')
</body>

</html>
