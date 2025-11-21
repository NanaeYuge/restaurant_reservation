<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title','Rese')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css','resources/js/app.js'])

    @yield('head')
    @stack('styles')
</head>
<body class="app" style="font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,'Helvetica Neue',Arial;">
    @php
        $hideHeader = request()->routeIs('owner.*') || request()->routeIs('admin.*');
    @endphp

    @if (! $hideHeader)
        <x-header />
    @endif

    <main class="container">
        @includeWhen(view()->exists('components.flash'), 'components.flash')
        @yield('content')
    </main>

    @yield('foot')
    @stack('scripts')

    <script>
    (function () {
        const body = document.body;
        const backdrop = document.querySelector('[data-menu-backdrop]');

        document.addEventListener('click', function (e) {
            if (e.target.closest('[data-menu-toggle]')) {
                body.classList.add('menu-open');
            }
            if (e.target.closest('[data-menu-close]') || (backdrop && e.target === backdrop)) {
                body.classList.remove('menu-open');
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') body.classList.remove('menu-open');
        });
    })();
    </script>
</body>
</html>
