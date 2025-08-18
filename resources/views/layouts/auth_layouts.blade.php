<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description"
        content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <meta>
    <title>IMS</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" href="{{ asset('app-assets/images/ico/apple-icon-120.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('app-assets/images/ico/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
        rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/charts/apexcharts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/toastr.min.css') }}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/themes/dark-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/themes/bordered-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/themes/semi-dark-layout.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/dashboard-ecommerce.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/charts/chart-apex.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css/colors.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets/css/plugins/extensions/ext-component-toastr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/fontawesome/css/fontawesome.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/fontawesome/css/all.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/toaster/toastr.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/clockpicker.css') }}" />
    <!--toast-->
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.min.css">

    <!-- Include DataTables JavaScript -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script>
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <!-- Custom styles for this template-->
    <!-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->
    <!-- Tempusdominus Bootstrap 4 -->
    @auth
        <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
        <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
        <script src="{{ asset('dist/js/script.js') }}"></script>
    @else
        <link rel="stylesheet"
            href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
        <!-- DataTables -->
        <link rel="stylesheet" type="text/css"
            href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
        <!-- Select2 -->
        <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
        <!-- iCheck -->
        <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
        <!-- JQVMap -->
        <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}">
        <link rel="stylesheet" href="{{ asset('dist/css/custom.css') }}">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
        <!-- summernote -->
        <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">

        <!-- jQuery -->
        <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
        <!-- SweetAlert2 -->
        <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
        <!-- Toastr -->
        <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
        <script src="{{ asset('dist/js/script.js') }}"></script>
    @endauth
    <!-- END: Custom CSS-->
    <!-- BEGIN: Yajra table css-->
    <style>
        /* Custom styles for time input */
        .styled-time-input {
            width: 100%;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .login {
            width: 70%;
            margin: auto;
        }

        .card {
            padding: 20px 50px;
            width: 100%;
        }

        .row {
            display: flex;
            width: 100%
        }
    </style>


    <!-- END: Yajra table css -->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->


<body>
    @auth
        <!-- BEGIN: Content-->
        <div id="main-content">
            @yield('content');
        </div>
        <!-- END: Content-->

        <div class="sidenav-overlay"></div>
        <div class="drag-target"></div>

        <!-- BEGIN: Footer-->
        <footer class="footer footer-static footer-light">
            <p class="clearfix mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT &copy; 2021<a
                        class="ml-25" href="https://1.envato.market/pixinvent_portfolio"
                        target="_blank">Pixinvent</a><span class="d-none d-sm-inline-block">, All rights
                        Reserved</span></span><span class="float-md-right d-none d-md-block">Hand-crafted & Made with<i
                        data-feather="heart"></i></span></p>
        </footer>
        <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
        <!-- END: Footer-->
    @else
        <div>
            @yield('auth_content');
        </div>
    @endauth
    <script src="/js/app.js"></script>
    {{-- <script src="{{ mix('js/app.js') }}"></script>- --}}
    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('app-assets/vendors/js/vendors.min.js') }}"></script>
    <!-- BEGIN Vendor JS-->
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('customjs/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('app-assets/toaster/toastr.js') }}"></script>
    {{-- <script src="{{ asset('app-assets/vendors/js/charts/apexcharts.min.js') }}"></script> --}}
    <script src="{{ asset('app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js') }}"></script>
    <script src="{{ asset('app-assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/jszip.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('app-assets/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/dataTables.select.min.js') }}"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('app-assets/js/core/app.js') }}"></script>
    <!-- END: Theme JS-->

    <!--End Load Custom Javascripts-->
    <script type="text/javascript" src="{{ asset('customjs/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('customjs/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('customjs/daterangepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('customjs/highlight.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('customjs/quill.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('customjs/jquery-ui.js') }}"></script>
    <script type="text/javascript" src="{{ asset('customjs/jquery.highlight.js') }}"></script>
    <script type="text/javascript" src="{{ asset('customjs/dataTables.searchHighlight.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
    <script type="text/javascript" src="{{ asset('customjs/datetimepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/clockpicker.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.clockpicker').clockpicker({
                donetext: 'Done', // Text for the "Done" button
                autoclose: true, // Close the picker when clicking outside
                twelvehour: true, // Use 24-hour format
            });
        });
    </script>
    {{-- <script type="text/javascript" src="{{ asset('app-assets/js/scripts/forms/form-repeater.js') }}"></script> --}}
    {{-- <script type="text/javascript" src="{{ asset('app-assets/js/scripts/pages/app-invoice.js') }}"></script> --
    <!-- BEGIN: Page JS-->
    {{-- <script src="{{ asset('app-assets/js/scripts/pages/dashboard-ecommerce.js') }}"></script> --}}

    <script type="text/javascript"
        src="https://cdn.datatables.net/plug-ins/1.11.6/features/searchHighlight/dataTables.searchHighlight.min.js">
    </script>
    <!-- END: Yajra table JS   -->

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




        function toastrMessage($type, $message, $info) {
            toastr[$type]($message, $info, {
                "closeButton": true,
                "debug": true,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-center",
                "preventDuplicates": true,
                "preventOpenDuplicates": true,
                "onclick": null,
                "showDuration": "3000",
                "hideDuration": "1000",
                "timeOut": "10000",
                "extendedTimeOut": "10000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            });
        }



        function currentDate() {
            var dates = "";
            $.get("/showcurrentdate", function(data) {
                dates = data.curdate;
                flatpickr('.flatpickr-basic', {
                    dateFormat: 'Y-m-d',
                    clickOpens: true,
                    maxDate: dates
                });
            });
        }

        currentDate();

        function closemodal() {
            $('#expirenotice').modal('hide');
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <div style="display: none">
        @yield('scripts')
    </div>
</body>
<!-- END: Body-->

</html>
