<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'EcoSport') }} - @yield('title', 'Экологичные спортивные товары')</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="icon" href="./favicon.ico" />
    @vite([
        'resources/css/app.css',
        'resources/js/product-page.js',
        'resources/js/components/notification.js',
        'resources/js/wishlist.js'
    ])
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
    <div id="notifications" class="fixed bottom-4 right-4 z-50 space-y-2"></div>

    @if (session('success') || session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @if(session('success'))
                    window.notificationManager.show('success', '{{ session('success') }}');
                @endif
                
                @if(session('error'))
                    window.notificationManager.show('error', '{{ session('error') }}');
                @endif
            });
        </script>
    @endif
</body>

</html>
