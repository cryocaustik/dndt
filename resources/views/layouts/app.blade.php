<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @csrf

    @if (Auth::check())
        <script>window.authUser={!! json_encode(Auth::user()); !!};</script>
    @else
        <script>window.authUser=null;</script>
    @endif

    <title>{{ config('app.name', 'DnDT') }} | @yield('title', 'DnD Tracker')</title>

    <!-- Scripts -->
    <script>
        window.Domain = "{{ env('APP_URL') }}";
        @if($errors)
            window.errors = {!! json_encode($errors->all()) !!}
        @endif
    </script>

    <script src="{{ asset('js/main.js') }}" defer></script>
    @stack('header-js')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
    <style>
        html{
            font-family: "Roboto", sans-serif;
        }
    </style>


    <!-- Styles -->
    @stack('css')
</head>
<body>
    <div id="app">
    </div>

    @stack('js')
</body>
</html>
