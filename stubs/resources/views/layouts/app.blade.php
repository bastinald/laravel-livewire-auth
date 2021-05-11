<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name') }}</title>

    <livewire:styles/>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <livewire:layouts.nav/>

    <main class="container my-4">
        @yield('content')
    </main>

    <livewire:scripts/>
    <livewire:modals/>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
