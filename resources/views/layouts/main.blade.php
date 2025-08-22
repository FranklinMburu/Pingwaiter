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
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @stack('styles')
</head>

<body class="bg-gray-50 min-h-screen font-lato">

    @if (session('success'))
        <div class="max-w-lg mx-auto mt-6 mb-2 px-4 py-3 bg-green-100 border border-green-300 text-green-800 rounded shadow">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="max-w-lg mx-auto mt-6 mb-2 px-4 py-3 bg-red-100 border border-red-300 text-red-800 rounded shadow">
            {{ session('error') }}
        </div>
    @endif
    @if (session('message'))
        <div class="max-w-lg mx-auto mt-6 mb-2 px-4 py-3 bg-blue-100 border border-blue-300 text-blue-800 rounded shadow">
            {{ session('message') }}
        </div>
    @endif

    @yield('content')

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @stack('scripts')
</body>

</html>
