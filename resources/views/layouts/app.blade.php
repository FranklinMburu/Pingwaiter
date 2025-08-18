<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @yield('title', config('app.name', 'Ping Waiter'))
    </title>
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <link rel="icon" type="image/ico" href="{{ URL::asset('favicon.ico') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <!-- Custom fonts for this template-->

    {{-- <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&family=Comfortaa:wght@500&display=swap""
        rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- Custom styles for this template-->
    {{-- <link href="{{ asset('admintemplate/admin_bootstrap/css/sb-admin-2.min.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('admintemplate/admin_bootstrap/vendor/datatables/dataTables.bootstrap4.min.css') }}"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admintemplate/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('admintemplate/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('admintemplate/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('admintemplate/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('admintemplate/plugins/select2/css/select2.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('admintemplate/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('admintemplate/plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    {{--    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}"> --}}
    {{--    <link rel="stylesheet" href="{{ asset('/dist/css/custom.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('/barcode.css') }}"> --}}
    <!-- overlayScrollbars -->
    {{-- <link rel="stylesheet" href="{{ asset('admintemplate/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}"> --}}
    <!-- Bootstrap CSS (required for styling Date Range Picker) -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css"> --}}
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" />

    @vite(['resources/css/app.css'])

    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">

    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('admintemplate/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('admintemplate/plugins/summernote/summernote-bs4.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet"
        href="{{ asset('admintemplate/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/scroller/2.3.0/css/scroller.dataTables.min.css">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" /> --}}
    <link rel="stylesheet" href="{{ asset('fancybox/fancybox.css') }}">

    <link rel="stylesheet" href="{{ URL::asset('assets/css/output.css') }}">
    <!-- Custom styles for this page -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

    @stack('styles')

    <meta name="restaurant-id" content="{{ auth()->user()->restaurant_id }}">
    @vite(['resources/js/global-sound.js'])
</head>

<body
    class="bg-body-light dark:bg-dark-body group-data-[theme-width=box]:container group-data-[theme-width=box]:max-w-screen-3xl xl:group-data-[theme-width=box]:px-3">
    <div id="loader" class="fixed inset-0 z-50 flex items-center justify-center bg-white/80 backdrop-blur-sm">
        <div class="text-xl font-semibold text-gray-700 animate-pulse">Loading...</div>
    </div>


    @auth
        @include('partials.menu.topbar')

        @include('partials.menu.sidebar')

        <div
            class="main-content group-data-[sidebar-size=lg]:xl:ml-[calc(theme('spacing.app-menu')_+_16px)] group-data-[sidebar-size=sm]:xl:ml-[calc(theme('spacing.app-menu-sm')_+_16px)] group-data-[theme-width=box]:xl:px-0 px-3 xl:px-4 ac-transition">
            @yield('content')
        </div>
    @endauth

    <script src="{{ URL::asset('assets/js/vendor/jquery.min.js') }}"></script>
    {{-- <script src="{{ URL::asset('assets/js/vendor/apexcharts.min.js') }}"></script> --}}
    <script src="{{ URL::asset('assets/js/vendor/flowbite.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/vendor/smooth-scrollbar/smooth-scrollbar.min.js') }}"></script>
    {{-- <script src="{{ URL::asset('assets/js/pages/dashboard-lms.js') }}"></script> --}}
    <script src="{{ URL::asset('assets/js/component/app-menu-bar.js') }}"></script>
    <script src="{{ URL::asset('assets/js/component/tab.js') }}"></script>
    <script src="{{ URL::asset('assets/js/switcher.js') }}"></script>
    <script src="{{ URL::asset('assets/js/layout.js') }}"></script>
    <script src="{{ URL::asset('assets/js/main.js') }}"></script>

    <script src="{{ asset('admintemplate/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('admintemplate/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('admintemplate/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('admintemplate/plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('admintemplate/dist/js/script.js') }}"></script>
    <script src="{{ asset('admintemplate/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('admintemplate/admin_bootstrap/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    {{-- <script src="{{ asset('admintemplate/admin_bootstrap/js/sb-admin-2.min.js') }}"></script> --}}

    <!-- Page level admintemplate/plugins -->
    <script src="{{ asset('admintemplate/admin_bootstrap/vendor/chart.js/Chart.min.js') }}"></script>

    <!-- Page level admintemplate/plugins -->
    <script src="{{ asset('admintemplate/admin_bootstrap/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admintemplate/admin_bootstrap/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.datatables.net/scroller/2.3.0/js/dataTables.scroller.min.js"></script>
    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

    <!-- Date Range Picker JS -->
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


    <!-- Page level custom scripts -->
    <script src="{{ asset('admintemplate/admin_bootstrap/js/demo/datatables-demo.js') }}"></script>
    <!-- jQuery -->

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('fancybox/fancybox.umd.js') }}"></script>

    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
    <script type="text/javascript">
        $(function() {
            cardSection = $('#page-block');
        });
        $(window).on('load', function() {
            if (typeof feather !== 'undefined') {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
