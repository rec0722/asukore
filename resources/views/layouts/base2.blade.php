<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title') | {{ config('app.name') }}</title>

  <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('dist/picker.min.css') }}">
  <link rel='stylesheet' type="text/css" href='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@5.10.0/main.css'>
</head>

<body>

  @if (Auth::check())
  @include('layouts.header')
  @else
  @include('layouts.header2')
  @endif

  <!-- Page Content goes here -->
  @yield('content')
  <!-- end container -->

  @include('layouts.footer')

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@5.10.0/main.js"></script>
  <script src="{{ asset('dist/moment.js') }}"></script>
  <script src="{{ asset('dist/picker.min.js') }}"></script>
  <script src="{{ asset('js/app.220121.js') }}"></script>
  <script src="{{ asset('js/bundle..220121js') }}"></script>
</body>

</html>
