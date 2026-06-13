@php
    $locale = app()->getLocale();
    $dir = config("locales.supported.$locale.dir", 'ltr');
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $dir }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Connexion')) — Riad Saveurs</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800;900&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">

    {{-- Applique le thème avant le rendu pour éviter tout flash clair/sombre. --}}
    <script>
        (function () {
            try {
                var t = localStorage.getItem('riad-theme') ||
                    (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', t);
            } catch (e) {}
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="auth-shell">
        <header class="auth-topbar">
            <a href="{{ route('menu.index') }}" class="topnav-brand">
                <span class="brand-mark"><x-icon name="sparkle" size="22" /></span>
                <span class="brand-name">Riad Saveurs</span>
            </a>
            <div class="auth-topbar-actions">
                <x-lang-switcher />
                <button class="icon-btn" data-theme-toggle aria-label="{{ __('Changer de thème') }}">
                    <span class="theme-sun"><x-icon name="sun" size="18" /></span>
                    <span class="theme-moon"><x-icon name="moon" size="18" /></span>
                </button>
            </div>
        </header>

        <main class="auth-main">
            @yield('content')
        </main>
    </div>

    @include('partials.flash')
</body>
</html>
