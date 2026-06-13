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
    <title>@yield('title', __('Cuisine marocaine traditionnelle')) — Riad Saveurs</title>

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
    <div class="shell-front">
        <header class="topnav">
            <div class="topnav-inner">
                <a href="{{ route('menu.index') }}" class="topnav-brand">
                    <span class="brand-mark"><x-icon name="sparkle" size="22" /></span>
                    <span class="brand-name">Riad Saveurs</span>
                </a>

                <nav class="topnav-links">
                    <a href="{{ route('menu.index') }}"
                       class="topnav-link {{ request()->routeIs('menu.*') ? 'is-active' : '' }}">{{ __('Menu') }}</a>
                    <a href="{{ route('suivi.index') }}"
                       class="topnav-link {{ request()->routeIs('suivi.*') ? 'is-active' : '' }}">{{ __('Suivre ma commande') }}</a>
                </nav>

                <div class="topnav-spacer"></div>

                <x-lang-switcher />

                <button class="icon-btn" data-theme-toggle aria-label="{{ __('Changer de thème') }}">
                    <span class="theme-sun"><x-icon name="sun" size="18" /></span>
                    <span class="theme-moon"><x-icon name="moon" size="18" /></span>
                </button>

                <a href="{{ route('panier.index') }}" class="icon-btn cart-btn" aria-label="{{ __('Panier') }}">
                    <x-icon name="cart" size="19" />
                    @php($nbPanier = \App\Support\Panier::count())
                    @if ($nbPanier > 0)
                        <span class="cart-count">{{ $nbPanier }}</span>
                    @endif
                </a>

                {{-- Espace gérant : visible uniquement pour un gérant connecté ; sinon bouton de connexion. --}}
                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost btn-sm topnav-auth">
                            <x-icon name="dashboard" size="16" /> {{ __('Espace gérant') }}
                        </a>
                    @endif
                @else
                    <a href="{{ route('admin.login') }}" class="btn btn-primary btn-sm topnav-auth">
                        <x-icon name="user" size="16" /> {{ __('Connexion') }}
                    </a>
                @endauth
            </div>
        </header>

        <main class="front-main">
            <div class="container">
                @yield('content')
            </div>
        </main>

        <footer class="front-foot">
            Riad Saveurs — {{ __('Cuisine marocaine traditionnelle') }} · {{ __('Tagines, Couscous, Pastillas & Thés') }} ·
            © {{ date('Y') }}
        </footer>
    </div>

    @include('partials.flash')
</body>
</html>
