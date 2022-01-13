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

  <!-- Page Content goes here -->
  @yield('content')
  <!-- end container -->

  @include('layouts.footer')

  <link href='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@5.10.0/main.css' rel='stylesheet' />
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@5.10.0/main.js"></script>
  <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script src="{{ asset('js/app.js') }}"></script>
  <script src="{{ asset('js/bundle.js') }}"></script>
  <script src="{{ asset('js/fullcalendar.js') }}"></script>
</body>

</html>
