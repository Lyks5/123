<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <title>{{ config('app.name', 'EcoSport') }} - @yield('title', 'Экологичные спортивные товары')</title>
    <link rel="icon" href="./favicon.ico" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite('resources/css/app.css')
</head>
<style>
    .menu {
        min-height: calc(100vh - (68px + 152px))
    }
</style>

<body>
<div class="min-h-screen flex flex-col">
        @include('components.navbar')
        
        <main class="flex-grow">
            @yield('content')
        </main>
        
        @include('components.footer')
    </div>
    <div id="toast-container" class="fixed top-4 right-4 z-50"></div>
</body>

</html>