<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name') }}</title>

    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
</head>

<body>

    @if (Auth::check())
        @include('layouts.header')
    @else
        @include('layouts.header2')
    @endif

    <div class="container">
        <!-- Page Content goes here -->

        @yield('content')

    </div><!-- end container -->

    @include('layouts.footer')

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/bundle.js') }}"></script>
</body>
</html>
