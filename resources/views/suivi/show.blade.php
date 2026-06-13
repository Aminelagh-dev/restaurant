@extends('layouts.app')

@section('title', 'Commande #' . $commande->id)

@section('content')
    @php
        $ordre = array_keys($statuts);
        $courant = array_search($commande->statut, $ordre);
    @endphp

    <div class="page-head">
        <div class="page-titles">
            <h1>Commande #{{ $commande->id }}</h1>
            <p>Passée le {{ $commande->date_commande->translatedFormat('d F Y à H:i') }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('suivi.index') }}" class="btn btn-ghost"><x-icon name="search" size="16" /> Suivre une autre commande</a>
            <a href="{{ route('menu.index') }}" class="btn btn-primary">Commander à nouveau</a>
        </div>
    </div>

    <div class="cart-grid">
        <div class="card card-pad">
            <h3 style="margin: 0 0 6px; font-size: 16px; font-weight: 800;">État de la livraison</h3>
            <p class="muted" style="margin: 0 0 22px; font-size: 13px;">
                Statut actuel :
                <span class="badge {{ $commande->statut === 'livree' ? 'badge-ok' : ($commande->statut === 'en_livraison' ? 'badge-amber' : 'badge-accent') }}">
                    {{ $commande->statutLabel() }}
                </span>
            </p>

            <ul class="timeline">
                @foreach ($statuts as $cle => $libelle)
                    @php($index = array_search($cle, $ordre))
                    <li class="tl-item {{ $index < $courant ? 'is-done' : ($index === $courant ? 'is-current' : 'is-upcoming') }}">
                        <span class="tl-dot">
                            @if ($index < $courant) <x-icon name="check" size="9" stroke="3" /> @endif
                        </span>
                        <div class="tl-title">{{ $libelle }}</div>
                        <div class="tl-sub">
                            @switch($cle)
                                @case('en_preparation') Vos plats sont préparés par nos chefs. @break
                                @case('en_livraison') Votre commande est en route. @break
                                @case('livree') Bon appétit ! @break
                            @endswitch
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="summary">
            <div class="card card-pad">
                <h3 style="margin: 0 0 14px; font-size: 16px; font-weight: 800;">Détail</h3>
                @foreach ($commande->lignes as $ligne)
                    <div class="summary-row">
                        <span>{{ $ligne->quantite }} × {{ $ligne->plat->nom ?? 'Plat supprimé' }}</span>
                        <span class="nowrap">{{ number_format($ligne->sous_total, 2, ',', ' ') }} DH</span>
                    </div>
                @endforeach
                <div class="summary-row total">
                    <span>Total</span>
                    <span>{{ number_format($commande->montant_total, 2, ',', ' ') }} DH</span>
                </div>

                <div style="margin-top: 18px; padding-top: 16px; border-top: 1px solid var(--border-soft); font-size: 13px; color: var(--ink-2);">
                    <div style="display: flex; gap: 8px; margin-bottom: 8px;"><x-icon name="map-pin" size="16" /> <span>{{ $commande->adresse_livraison }}</span></div>
                    <div style="display: flex; gap: 8px;"><x-icon name="user" size="16" /> <span>{{ $commande->nom_recepteur }} · {{ $commande->telephone_recepteur }}</span></div>
                </div>
            </div>
        </div>
    </div>
@endsection
