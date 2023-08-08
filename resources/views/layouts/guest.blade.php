<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <link rel="icon" type="image/png" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}"/>

    <style>

    </style>

    @stack('stylesheets')

<!-- Scripts -->
    {{--        @vite(['resources/css/app.css', 'resources/js/app.js'])--}}
</head>
<body>
<div class="">
    {{ $slot }}
</div>



<script src="{{ asset('js/app.js') }}"></script>



@stack('scripts')

</body>
</html>
