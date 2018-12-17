<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Admin | @yield('title') </title>

        <link rel="icon" href="{!! asset('images/favicon.ico') !!}">

        <link href="{{ asset(mix('admin/css/app.css')) }}" rel="stylesheet">
    </head>
    <body>
        <div id="app">
            <!-- Wrapper-->
            <div id="wrapper">

                <!-- Navigation -->
                @include('admin::back.includes.navigation')

                <!-- Page wraper -->
                <div id="page-wrapper" class="gray-bg">

                    <!-- Page wrapper -->
                    @include('admin::back.includes.topnavbar')

                    <!-- Breadcrumbs -->
                    @include('admin::back.partials.breadcrumbs.app')

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
        </div>

        <div id="modules-components">
            <div :id="moduleName+'_components'" v-for="(module, moduleName) in modules" :key="moduleName">
                <component
                    :is="component.name"
                    v-for="component in module.components"
                    :key="component.name" :ref="moduleName+'_'+component.name"
                    v-bind="component.data"
                />
            </div>
        </div>

        @routes('back')

        <script src="{{ asset(mix('admin/js/manifest.js')) }}"></script>
        <script src="{{ asset(mix('admin/js/vendor.js')) }}"></script>
        <script src="{{ asset(mix('admin/js/app.js')) }}"></script>

        @stack('scripts')
    </body>
</html>
