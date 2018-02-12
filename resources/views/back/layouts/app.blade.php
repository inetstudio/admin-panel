<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Admin | @yield('title') </title>

        <link rel="icon" href="{!! asset('images/favicon.ico') !!}">

        <link href="{!! asset('admin/css/bootstrap.min.css') !!}" rel="stylesheet">
        <link href="{!! asset('admin/font-awesome/css/font-awesome.css') !!}" rel="stylesheet">

        @stack('pre_styles')

        <link href="{!! asset('admin/css/animate.css') !!}" rel="stylesheet">
        <link href="{!! asset('admin/css/style.css') !!}" rel="stylesheet">

        <!-- Sweet Alert -->
        <link href="{!! asset('admin/css/plugins/sweetalert/sweetalert.css') !!}" rel="stylesheet">

        <!-- Toastr -->
        <link href="{!! asset('admin/css/plugins/toastr/toastr.min.css') !!}" rel="stylesheet">

        @stack('styles')

        <!-- CUSTOM STYLES -->
        <link href="{!! asset('admin/css/custom.css') !!}" rel="stylesheet">
    </head>
    <body>

        <!-- Wrapper-->
        <div id="wrapper">

            <!-- Navigation -->
            @include('admin::back.includes.navigation')

            <!-- Page wraper -->
            <div id="page-wrapper" class="gray-bg">

                <!-- Page wrapper -->
                @include('admin::back.includes.topnavbar')

                <!-- Breadcrumbs -->
                @include('admin::back.partials.breadcrumbs')

                <!-- Main view  -->
                @yield('content')

                <!-- Footer -->
                @include('admin::back.includes.footer')

            </div>
            <!-- End page wrapper-->

        </div>
        <!-- End wrapper-->

        <!-- Modals-->
        @stack('modals')

        <!-- Mainly scripts -->
        <script src="{!! asset('admin/js/jquery-3.1.1.min.js') !!}"></script>
        <script src="{!! asset('admin/js/bootstrap.min.js') !!}"></script>
        <script src="{!! asset('admin/js/vue.js') !!}"></script>
        <script src="{!! asset('admin/js/plugins/metisMenu/jquery.metisMenu.js') !!}"></script>
        <script src="{!! asset('admin/js/plugins/slimscroll/jquery.slimscroll.min.js') !!}"></script>

        <!-- Custom and plugin javascript -->
        <script src="{!! asset('admin/js/inspinia.js') !!}"></script>
        <script src="{!! asset('admin/js/plugins/pace/pace.min.js') !!}"></script>

        <!-- Sweet alert -->
        <script src="{!! asset('admin/js/plugins/sweetalert/sweetalert.min.js') !!}"></script>

        <!-- Toastr -->
        <script src="{!! asset('admin/js/plugins/toastr/toastr.min.js') !!}"></script>

        <!-- Lazy Load XT -->
        <script src="{!! asset('admin/js/plugins/lazyloadxt/jquery.lazyloadxt.min.js') !!}"></script>

        <!-- Holder -->
        <script src="{!! asset('admin/js/plugins/holder/holder.min.js') !!}"></script>

        @stack('scripts')

        @routes('back')

        <!-- SerializeJSON -->
        <script src="{!! asset('admin/js/plugins/serializeJSON/jquery.serializejson.min.js') !!}"></script>

        <!-- MD5 -->
        <script src="{!! asset('admin/js/plugins/md5/md5.min.js') !!}"></script>

        <!-- UUID -->
        <script src="{!! asset('admin/js/plugins/uuid/uuid.core.js') !!}"></script>

        <!-- ADMIN SCRIPTS -->
        <script src="{!! asset('admin/js/custom.js') !!}"></script>

        <!-- MODULES SCRIPTS -->
        @stack('custom_scripts')

    </body>
</html>
