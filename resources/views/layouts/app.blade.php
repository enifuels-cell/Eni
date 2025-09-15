<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Eni Members') }}</title>
    <meta name="theme-color" content="#FFCD00">
    <link rel="manifest" href="/manifest.webmanifest">
    <!-- Favicon / PWA icons -->
    <link rel="icon" href="/eni.png" type="image/png">
    <link rel="apple-touch-icon" href="/eni.png">
    <meta name="application-name" content="{{ config('app.name', 'Eni Members') }}">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name', 'Eni Members') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="msapplication-TileColor" content="#FFCD00">
    <meta name="msapplication-TileImage" content="/eni.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-eni-dark">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-eni-dark border-b border-eni-yellow/20 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <h2 class="font-semibold text-xl text-eni-yellow leading-tight">
                            {{ $header }}
                        </h2>
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
            
            <!-- Global Footer -->
            @include('components.footer')
        </div>
        
        <!-- Footer Modals -->
        @include('components.footer-modals')

        <!-- Service Worker registration for PWA (Workbox generated at /build/sw.js) -->
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function() {
                    navigator.serviceWorker.register('/build/sw.js').then(function(reg) {
                        console.log('Service worker registered with scope:', reg.scope);
                    }).catch(function(err) {
                        console.warn('Service worker registration failed:', err);
                    });
                });
            }
        </script>
    </body>
</html>
