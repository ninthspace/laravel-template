<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=no"/>
    {{--<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>--}}
    <meta name="format-detection" content="telephone=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" type="text/css" href="{{ manifest('assets', 'vendor.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ manifest('assets', 'application.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ manifest('assets', 'admin.css') }}">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link media="print" onload="this.media='all'" href="https://fonts.googleapis.com/css?family=Nunito&display=swap"
          rel="stylesheet">
    <title>
        @yield('title') Tramper
    </title>
    @livewireStyles
    <script src="{{ manifest('assets', 'application.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.js" defer></script>
</head>
<body class="bg-gray-200">
@yield('bookmarks')
<livewire:floorshow-flash/>
@yield('body')
@isset($breadcrumbs)
    {!! $breadcrumbs->render() !!}
@endisset
{{-- publish the Livewire assets, but still use this: --}}
@livewireScripts
<script src="/vendor/floorshow/javascript/floorshowListResults.js"></script>
@stack('scripts')
</body>
</html>
