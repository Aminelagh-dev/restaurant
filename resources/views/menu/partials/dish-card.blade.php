@php($epuise = $plat->estEpuise())

<article class="dish-card">
    <a href="{{ route('menu.show', $plat) }}" class="dish-media">
        @if ($plat->image_url)
            <img src="{{ $plat->image_url }}" alt="{{ $plat->nom }}" loading="lazy">
        @else
            <span class="dish-media-fallback"><x-icon name="utensils" size="34" /></span>
        @endif
        <span class="dish-flag">
            @if ($epuise)
                <span class="badge badge-red"><span class="dot"></span> {{ __('Épuisé') }}</span>
            @else
                <span class="badge badge-ok"><span class="dot"></span> {{ __('Disponible') }}</span>
            @endif
        </span>
    </a>

    <div class="dish-body">
        <div class="dish-top">
            <div>
                <div class="dish-cat">{{ $plat->categorie->nom ?? '' }}</div>
                <a href="{{ route('menu.show', $plat) }}" class="dish-name">{{ $plat->nom }}</a>
            </div>
        </div>
        <p class="dish-desc">{{ $plat->description }}</p>
        <div class="dish-meta">
            <span><x-icon name="clock" size="15" /> {{ $plat->temps_preparation }} {{ __('min') }}</span>
        </div>

        <div class="dish-foot">
            <span class="price">{{ number_format($plat->prix, 2, ',', ' ') }} <small>{{ __('DH') }}</small></span>
            @if ($epuise)
                <button class="btn btn-ghost btn-sm" disabled>{{ __('Indisponible') }}</button>
            @else
                <form method="POST" action="{{ route('panier.store', $plat) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm">
                        <x-icon name="plus" size="15" stroke="2.2" /> {{ __('Ajouter') }}
                    </button>
                </form>
            @endif
        </div>
    </div>
</article>
