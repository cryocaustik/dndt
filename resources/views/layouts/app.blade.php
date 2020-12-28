<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DnDT | @yield('title', 'DnD Tracker')</title>

    {{-- fontawesome --}}
    <link rel="stylesheet" href="{{ asset('fa5/css/all.css') }}">

    {{-- AdminLte --}}
    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.min.css') }}">
{{--    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">--}}
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    @stack('css')
</head>
<body class="sidebar-mini">
    <div class="wrapper">
        @include('includes.header')
        @include('includes.sidenav')

        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">
                                @yield('page_header')
                                <small>@yield('page_description')</small>
                            </h1>
                        </div>
                    </div>
                </div>
            </section>

            <div class="container-fluid">
                <!-- Notifications -->
                @include('includes.notifications')

                <!-- Main content -->
                @yield('content')
            </div>
        </div>
    </div>

    {{-- AdminLte --}}
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte/js/adminlte.min.js') }}"></script>
    @stack('js')
</body>
</html>
