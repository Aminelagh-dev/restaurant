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
    <title>@yield('title', __('Administration')) — Riad Saveurs</title>

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
    <div class="app">
        @include('partials.admin-sidenav')

        <div class="main">
            <header class="topbar">
                <div class="crumb">
                    <span class="crumb-dim">{{ __('Administration') }}</span>
                    <x-icon name="chevron-right" size="14" stroke="2" />
                    <span class="crumb-cur">@yield('crumb', __('Tableau de bord'))</span>
                </div>
                <div class="topbar-right">
                    <x-lang-switcher />
                    <a href="{{ route('admin.commandes.index') }}" class="icon-btn" aria-label="{{ __('Commandes') }}">
                        <x-icon name="bell" size="19" />
                        @if (\App\Models\Commande::where('statut', \App\Models\Commande::STATUT_PREPARATION)->exists())
                            <span class="icon-dot"></span>
                        @endif
                    </a>
                    <button class="icon-btn" data-theme-toggle aria-label="{{ __('Changer de thème') }}">
                        <span class="theme-sun"><x-icon name="sun" size="19" /></span>
                        <span class="theme-moon"><x-icon name="moon" size="19" /></span>
                    </button>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="icon-btn" aria-label="{{ __('Déconnexion') }}" title="{{ __('Déconnexion') }}">
                            <x-icon name="logout" size="19" />
                        </button>
                    </form>
                    @php($u = auth()->user())
                    <span class="avatar topbar-avatar">{{ strtoupper(mb_substr($u->prenom ?: $u->nom, 0, 1).mb_substr($u->nom, 0, 1)) }}</span>
                </div>
            </header>

            <main class="content">
                <div class="page">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @include('partials.flash')
</body>
</html>
