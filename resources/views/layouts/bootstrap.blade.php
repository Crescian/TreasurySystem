<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="{{ asset('css/fontawesome-free-6.7.1-web/css/all.css') }}">

    <!-- jQuery -->
    <script src="{{ asset('js/jquery.min.js') }}"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">
    <script src="{{ asset('js/sweetalert2.min.js') }}"
        integrity="sha512-a/ljmGyCvVDl+QZXCxw/6hKcG4V7Syo7qmb9lUFTwrP12lCCItvQKeTMBMjtpa+3RE6UZ7gk+/IZzj4H04y4ng=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <!-- Vite (optional: comment out if not needed in Bootstrap pages) -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>

<style>
    .inputsDetail {
        display: none;
    }
</style>

<body>
    {{-- Navbar --}}
    @include('layouts.navigation')

    {{-- Page Header --}}
    @isset($header)
        <header class="bg-white shadow-sm border-bottom py-3 mb-4">
            <div class="container">
                {!! $header !!}
            </div>
        </header>
    @endisset

    {{-- Page Content --}}
    <main class="container mb-5">
        {{ $slot }}
    </main>
</body>

</html>
