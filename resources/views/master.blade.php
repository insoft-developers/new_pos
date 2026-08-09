<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">

<head>
    <meta charset="utf-8" />
    <title>i-Kasir | Aplikasi Point Of Sale Sederhana</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Drezoc - Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="MyraStudio" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
    <!-- App css -->
    <link href="{{ asset('template') }}/assets/css/style.min.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('template') }}/assets/css/icons.min.css" rel="stylesheet" type="text/css">
    <script src="{{ asset('template') }}/assets/js/config.js"></script>

    @if ($view == 'dashboard')
        <link href="{{ asset('template') }}/assets/libs/morris.js/morris.css" rel="stylesheet" type="text/css" />
    @else
        <!-- third party css -->
        <link href="{{ asset('template') }}/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css"
            rel="stylesheet" type="text/css" />
        <link href="{{ asset('template') }}/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css"
            rel="stylesheet" type="text/css" />
        <link href="{{ asset('template') }}/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css"
            rel="stylesheet" type="text/css" />
        <link href="{{ asset('template') }}/assets/libs/datatables.net-select-bs5/css//select.bootstrap5.min.css"
            rel="stylesheet" type="text/css" />
        <!-- third party css end -->
    @endif

    @include('css')
</head>

<body>

    <!-- Begin page -->
    <div class="layout-wrapper">

        <!-- ========== Left Sidebar ========== -->

        @include('sidebar')


        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="page-content">

            @include('topbar')
            @yield('content')
            @include('footer')

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- App js -->
    <script src="{{ asset('template') }}/assets/js/vendor.min.js"></script>
    <script src="{{ asset('template') }}/assets/js/app.js"></script>

    @if ($view == 'dasboard')
        <!-- Jquery Sparkline Chart  -->
        <script src="{{ asset('template') }}/assets/libs/jquery-sparkline/jquery.sparkline.min.js"></script>

        <!-- Jquery-knob Chart Js-->
        <script src="{{ asset('template') }}/assets/libs/jquery-knob/jquery.knob.min.js"></script>


        <!-- Morris Chart Js-->
        <script src="{{ asset('template') }}/assets/libs/morris.js/morris.min.js"></script>

        <script src="{{ asset('template') }}/assets/libs/raphael/raphael.min.js"></script>

        <!-- Dashboard init-->
        <script src="{{ asset('template') }}/assets/js/pages/dashboard.js"></script>
    @else
        <!-- third party js -->
        <script src="{{ asset('template') }}/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="{{ asset('template') }}/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
        <script src="{{ asset('template') }}/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="{{ asset('template') }}/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js">
        </script>
        <script src="{{ asset('template') }}/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="{{ asset('template') }}/assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
        <script src="{{ asset('template') }}/assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="{{ asset('template') }}/assets/libs/datatables.net-buttons/js/buttons.flash.min.js"></script>
        <script src="{{ asset('template') }}/assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="{{ asset('template') }}/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
        <script src="{{ asset('template') }}/assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
        <script src="{{ asset('template') }}/assets/libs/pdfmake/build/pdfmake.min.js"></script>
        <script src="{{ asset('template') }}/assets/libs/pdfmake/build/vfs_fonts.js"></script>
        <!-- third party js ends -->

         <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Datatables js -->
        {{-- <script src="{{ asset('template') }}/assets/js/pages/datatables.js?v=3"></script> --}}
    @endif

    @stack('scripts')

</body>

</html>
