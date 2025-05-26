<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ config('app.url') }}">
    <meta name="api-url" content="{{ config('app.url') }}/api">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/index.tsx'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('admin.layouts.navigation')

        <main>
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>