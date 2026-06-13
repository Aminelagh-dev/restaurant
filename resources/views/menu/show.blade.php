@extends('layouts.app')

@section('title', $plat->nom)

@section('content')
    @php
        $epuise = $plat->estEpuise();
        $ingredients = collect(preg_split('/[,\n;]+/', (string) $plat->ingredients))
            ->map(fn ($i) => trim($i))
            ->filter()
            ->values();
    @endphp

    <a href="{{ route('menu.index') }}" class="btn btn-ghost btn-sm" style="margin-bottom: 18px;">
        <x-icon name="arrow-left" size="16" /> Retour au menu
    </a>

    <div class="detail-grid">
        <div class="detail-media">
            @if ($plat->image_url)
                <img src="{{ $plat->image_url }}" alt="{{ $plat->nom }}">
            @else
                <div class="dish-media-fallback" style="position: static; height: 100%;">
                    <x-icon name="utensils" size="56" />
                </div>
            @endif
        </div>

        <div>
            <div class="dish-cat" style="margin-bottom: 8px;">{{ $plat->categorie->nom ?? 'Plat' }}</div>
            <h1 style="margin: 0 0 10px; font-size: 30px; font-weight: 800; letter-spacing: -.03em;">{{ $plat->nom }}</h1>

            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                <span class="price" style="font-size: 24px;">{{ number_format($plat->prix, 2, ',', ' ') }} <small>DH</small></span>
                @if ($epuise)
                    <span class="badge badge-red"><span class="dot"></span> Épuisé</span>
                @else
                    <span class="badge badge-ok"><span class="dot"></span> Disponible</span>
                @endif
            </div>

            <p style="font-size: 14.5px; line-height: 1.65; color: var(--ink-2); margin: 0 0 4px;">{{ $plat->description }}</p>

            <div class="spec-list">
                <div class="spec">
                    <div class="spec-val">{{ $plat->temps_preparation }}'</div>
                    <div class="spec-lbl">Préparation</div>
                </div>
                <div class="spec">
                    <div class="spec-val">{{ number_format($plat->prix, 0, ',', ' ') }}</div>
                    <div class="spec-lbl">Prix (DH)</div>
                </div>
                <div class="spec">
                    <div class="spec-val">{{ $epuise ? '0' : $plat->stock }}</div>
                    <div class="spec-lbl">En stock</div>
                </div>
            </div>

            @if ($ingredients->isNotEmpty())
                <h3 style="font-size: 14px; font-weight: 700; margin: 4px 0 0;">Ingrédients principaux</h3>
                <div class="ingredient-chips">
                    @foreach ($ingredients as $ingredient)
                        <span class="chip">{{ $ingredient }}</span>
                    @endforeach
                </div>
            @endif

            <div style="margin-top: 24px;">
                @if ($epuise)
                    <button class="btn btn-ghost btn-block" disabled>Actuellement indisponible</button>
                @else
                    <form method="POST" action="{{ route('panier.store', $plat) }}"
                          style="display: flex; gap: 12px; align-items: center;">
                        @csrf
                        <div class="qty" data-qty data-min="1" data-max="{{ max(1, $plat->stock) }}">
                            <button type="button" data-step="-1" aria-label="Moins"><x-icon name="minus" size="14" stroke="2.2" /></button>
                            <input type="hidden" name="quantite" value="1">
                            <span class="val">1</span>
                            <button type="button" data-step="1" aria-label="Plus"><x-icon name="plus" size="14" stroke="2.2" /></button>
                        </div>
                        <button type="submit" class="btn btn-primary" style="flex: 1; justify-content: center;">
                            <x-icon name="cart" size="17" /> Ajouter au panier
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    @if ($similaires->isNotEmpty())
        <div class="section-head">
            <h2>Dans la même catégorie</h2>
            <span class="rule"></span>
        </div>
        <div class="menu-grid">
            @foreach ($similaires as $similaire)
                @include('menu.partials.dish-card', ['plat' => $similaire])
            @endforeach
        </div>
    @endif
@endsection
