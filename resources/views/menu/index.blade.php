@extends('layouts.app')

@section('title', __('Menu gastronomique'))

@section('content')
    <section class="hero">
        <div class="hero-inner">
            <span class="kicker"><x-icon name="sparkle" size="15" /> {{ __('Saveurs du Maroc') }}</span>
            <h1>{{ __('La cuisine marocaine traditionnelle, livrée chez vous') }}</h1>
            <p>{{ __('Tagines mijotés, couscous du vendredi, pastillas croustillantes et thés à la menthe. Des recettes authentiques préparées par nos chefs, à commander en quelques clics.') }}</p>
            <a href="#carte" class="btn btn-primary">{{ __('Découvrir la carte') }} <x-icon name="arrow-right" size="16" stroke="2" /></a>
        </div>
    </section>

    <form method="GET" action="{{ route('menu.index') }}" class="search-box" style="margin-bottom: 8px;">
        <x-icon name="search" size="20" />
        <input type="text" name="q" value="{{ $recherche }}" placeholder="{{ __('Rechercher un plat (tagine, couscous, pastilla…)') }}">
        @if ($recherche !== '')
            <a href="{{ route('menu.index') }}" class="ghost-icon" aria-label="{{ __('Effacer') }}"><x-icon name="x" size="16" /></a>
        @endif
    </form>
    @if ($recherche !== '')
        <p class="muted" style="margin: 0 4px 8px; font-size: 12.5px;">
            {{ __(':count résultat(s) pour « :q »', ['count' => $total, 'q' => $recherche]) }}
        </p>
    @endif

    <div id="carte">
        @forelse ($categories as $categorie)
            <div class="section-head">
                <h2>{{ $categorie->nom }}</h2>
                <span class="count-badge">{{ $categorie->plats->count() }}</span>
                <span class="rule"></span>
            </div>

            <div class="menu-grid">
                @foreach ($categorie->plats as $plat)
                    @include('menu.partials.dish-card', ['plat' => $plat])
                @endforeach
            </div>
        @empty
            <div class="empty-state card" style="margin-top: 24px;">
                <span class="empty-ico"><x-icon name="utensils" size="26" /></span>
                <h3>{{ __('Aucun plat trouvé') }}</h3>
                <p>{{ __('Aucune recette ne correspond à votre recherche pour le moment.') }}</p>
                @if ($recherche !== '')
                    <a href="{{ route('menu.index') }}" class="btn btn-ghost">{{ __('Voir toute la carte') }}</a>
                @endif
            </div>
        @endforelse
    </div>
@endsection
