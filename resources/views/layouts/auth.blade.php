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
</head>

<body class="bg-gray-50 min-h-screen font-lato">
    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Left side with background image (hidden on mobile) -->
        {{-- <div class="hidden lg:flex lg:w-1/2 bg-image-overlay">
            <div class="flex flex-col justify-center items-center text-white p-12 w-full">
                <div class="max-w-md text-center">
                    <h1 class="text-5xl font-bold mb-6 font-playfair leading-tight">Connecting Restaurants & Customers
                    </h1>
                    <p class="text-xl mb-8 opacity-90">The complete platform where restaurants thrive and customers
                        discover amazing dining experiences.</p>
                    <div class="w-24 h-1 bg-white rounded mx-auto mb-8 opacity-75"></div>
                    <div class="space-y-4">
                        <div class="flex items-center justify-center">
                            <i class="bi bi-check-circle-fill mr-3 text-[#8065ee] text-lg"></i>
                            <span class="text-lg">Streamlined customer management</span>
                        </div>
                        <div class="flex items-center justify-center">
                            <i class="bi bi-check-circle-fill mr-3 text-[#8065ee] text-lg"></i>
                            <span class="text-lg">Integrated loyalty programs</span>
                        </div>
                        <div class="flex items-center justify-center">
                            <i class="bi bi-check-circle-fill mr-3 text-[#8065ee] text-lg"></i>
                            <span class="text-lg">Real-time order notifications</span>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Right side with form -->
        <div class="w-full mx-auto lg:w-1/2 py-8 px-4 md:px-8 lg:px-12 flex flex-col justify-center overflow-y-auto">
            @yield('content')
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @stack('scripts')
</body>

</html>
