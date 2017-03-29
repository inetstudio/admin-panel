<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin | @yield('title') </title>

        <link rel="icon" href="{!! asset('images/favicon.ico') !!}">

        <link href="{!! asset('admin/css/bootstrap.min.css') !!}" rel="stylesheet">
        <link href="{!! asset('admin/font-awesome/css/font-awesome.css') !!}" rel="stylesheet">

        <link href="{!! asset('admin/css/animate.css') !!}" rel="stylesheet">
        <link href="{!! asset('admin/css/style.css') !!}" rel="stylesheet">

        @section('styles')
        @show
    </head>
    <body>

        <!-- Wrapper-->
        <div id="wrapper">

            <!-- Navigation -->
            @include('back.layouts.navigation')

            <!-- Page wraper -->
            <div id="page-wrapper" class="gray-bg">

                <!-- Page wrapper -->
                @include('back.layouts.topnavbar')

                <!-- Main view  -->
                @yield('content')

                <!-- Footer -->
                @include('back.layouts.footer')

            </div>
            <!-- End page wrapper-->

        </div>
        <!-- End wrapper-->

        <!-- Modals-->
        @yield('modals')

        <!-- Mainly scripts -->
        <script src="{!! asset('admin/js/jquery-3.1.1.min.js') !!}"></script>
        <script src="{!! asset('admin/js/bootstrap.min.js') !!}"></script>
        <script src="{!! asset('admin/js/plugins/metisMenu/jquery.metisMenu.js') !!}"></script>
        <script src="{!! asset('admin/js/plugins/slimscroll/jquery.slimscroll.min.js') !!}"></script>

        <!-- Custom and plugin javascript -->
        <script src="{!! asset('admin/js/inspinia.js') !!}"></script>
        <script src="{!! asset('admin/js/plugins/pace/pace.min.js') !!}"></script>

        @section('scripts')
        @show

    </body>
</html>
