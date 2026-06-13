@extends('layouts.app')

@section('title', 'Mon panier')

@section('content')
    <div class="page-head">
        <div class="page-titles">
            <h1>Mon panier</h1>
            <p>Vérifiez vos plats et ajustez les quantités avant de commander.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('menu.index') }}" class="btn btn-ghost"><x-icon name="arrow-left" size="16" /> Continuer mes achats</a>
        </div>
    </div>

    @if ($lignes->isEmpty())
        <div class="empty-state card">
            <span class="empty-ico"><x-icon name="cart" size="28" /></span>
            <h3>Votre panier est vide</h3>
            <p>Parcourez notre carte et ajoutez vos plats marocains préférés.</p>
            <a href="{{ route('menu.index') }}" class="btn btn-primary">Voir le menu</a>
        </div>
    @else
        <div class="cart-grid">
            <div class="card">
                @foreach ($lignes as $ligne)
                    @php($plat = $ligne['plat'])
                    <div class="cart-line">
                        @if ($plat->image_url)
                            <img src="{{ $plat->image_url }}" alt="{{ $plat->nom }}" class="cart-thumb">
                        @else
                            <span class="cart-thumb thumb-fallback" style="display: grid;"><x-icon name="utensils" size="22" /></span>
                        @endif

                        <div class="cart-info">
                            <a href="{{ route('menu.show', $plat) }}" class="nm">{{ $plat->nom }}</a>
                            <div class="pu">{{ number_format($plat->prix, 2, ',', ' ') }} DH l'unité</div>
                        </div>

                        <form method="POST" action="{{ route('panier.update', $plat) }}">
                            @csrf
                            @method('PATCH')
                            <div class="qty" data-qty data-min="1" data-max="99" data-autosubmit>
                                <button type="button" data-step="-1" aria-label="Moins"><x-icon name="minus" size="14" stroke="2.2" /></button>
                                <input type="hidden" name="quantite" value="{{ $ligne['quantite'] }}">
                                <span class="val">{{ $ligne['quantite'] }}</span>
                                <button type="button" data-step="1" aria-label="Plus"><x-icon name="plus" size="14" stroke="2.2" /></button>
                            </div>
                        </form>

                        <div class="text-right nowrap" style="min-width: 92px;">
                            <strong>{{ number_format($ligne['sous_total'], 2, ',', ' ') }} DH</strong>
                        </div>

                        <form method="POST" action="{{ route('panier.destroy', $plat) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="ghost-icon danger" aria-label="Retirer"><x-icon name="trash" size="18" /></button>
                        </form>
                    </div>
                @endforeach
            </div>

            <div class="summary">
                <div class="card card-pad">
                    <h3 style="margin: 0 0 12px; font-size: 16px; font-weight: 800;">Récapitulatif</h3>
                    <div class="summary-row">
                        <span>Sous-total ({{ $lignes->sum('quantite') }} article{{ $lignes->sum('quantite') > 1 ? 's' : '' }})</span>
                        <span>{{ number_format($total, 2, ',', ' ') }} DH</span>
                    </div>
                    <div class="summary-row">
                        <span>Livraison</span>
                        <span class="badge badge-ok">Offerte</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>{{ number_format($total, 2, ',', ' ') }} DH</span>
                    </div>

                    <a href="{{ route('checkout.create') }}" class="btn btn-primary btn-block" style="margin-top: 16px;">
                        Passer la commande <x-icon name="arrow-right" size="16" stroke="2" />
                    </a>

                    <form method="POST" action="{{ route('panier.clear') }}" data-confirm="Vider entièrement le panier ?" style="margin-top: 10px;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-ghost btn-block btn-sm">Vider le panier</button>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
