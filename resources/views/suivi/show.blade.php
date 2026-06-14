@extends('layouts.app')

@section('title', __('Commande #:id', ['id' => $commande->id]))

@section('content')
    @php
        $ordre = array_keys($statuts);
        $courant = array_search($commande->statut, $ordre);
    @endphp

    <div class="page-head">
        <div class="page-titles">
            <h1>{{ __('Commande #:id', ['id' => $commande->id]) }}</h1>
            <p>{{ __('Passée le :date', ['date' => $commande->date_commande->translatedFormat(__('d F Y à H:i'))]) }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('suivi.index') }}" class="btn btn-ghost"><x-icon name="search" size="16" /> {{ __('Suivre une autre commande') }}</a>
            <a href="{{ route('menu.index') }}" class="btn btn-primary">{{ __('Commander à nouveau') }}</a>
        </div>
    </div>

    <div class="cart-grid">
        <div class="card card-pad">
            <h3 style="margin: 0 0 6px; font-size: 16px; font-weight: 800;">{{ __('État de la livraison') }}</h3>
            <p class="muted" style="margin: 0 0 22px; font-size: 13px;">
                {{ __('Statut actuel :') }}
                <span class="badge {{ $commande->statut === 'livree' ? 'badge-ok' : ($commande->statut === 'en_livraison' ? 'badge-amber' : 'badge-accent') }}">
                    {{ __($commande->statutLabel()) }}
                </span>
            </p>

            <ul class="timeline">
                @foreach ($statuts as $cle => $libelle)
                    @php($index = array_search($cle, $ordre))
                    <li class="tl-item {{ $index < $courant ? 'is-done' : ($index === $courant ? 'is-current' : 'is-upcoming') }}">
                        <span class="tl-dot">
                            @if ($index < $courant) <x-icon name="check" size="9" stroke="3" /> @endif
                        </span>
                        <div class="tl-title">{{ __($libelle) }}</div>
                        <div class="tl-sub">
                            @switch($cle)
                                @case('en_attente') {{ __('Votre commande a bien été reçue.') }} @break
                                @case('en_preparation') {{ __('Vos plats sont préparés par nos chefs.') }} @break
                                @case('en_livraison') {{ __('Votre commande est en route.') }} @break
                                @case('livree') {{ __('Bon appétit !') }} @break
                            @endswitch
                        </div>
                        @if ($datesStatuts->has($cle))
                            <div class="tl-time"><x-icon name="clock" size="12" /> {{ $datesStatuts[$cle]->translatedFormat(__('d F Y à H:i')) }}</div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="summary">
            <div class="card card-pad">
                <h3 style="margin: 0 0 14px; font-size: 16px; font-weight: 800;">{{ __('Détail') }}</h3>
                @foreach ($commande->lignes as $ligne)
                    <div class="summary-row">
                        <span>{{ $ligne->quantite }} × {{ $ligne->plat->nom ?? __('Plat supprimé') }}</span>
                        <span class="nowrap">{{ number_format($ligne->sous_total, 2, ',', ' ') }} {{ __('DH') }}</span>
                    </div>
                @endforeach
                <div class="summary-row total">
                    <span>{{ __('Total') }}</span>
                    <span>{{ number_format($commande->montant_total, 2, ',', ' ') }} {{ __('DH') }}</span>
                </div>

                <div style="margin-top: 18px; padding-top: 16px; border-top: 1px solid var(--border-soft); font-size: 13px; color: var(--ink-2);">
                    <div style="display: flex; gap: 8px; margin-bottom: 8px;"><x-icon name="map-pin" size="16" /> <span>{{ $commande->adresse_livraison }}</span></div>
                    <div style="display: flex; gap: 8px;"><x-icon name="user" size="16" /> <span>{{ $commande->nom_recepteur }} · {{ $commande->telephone_recepteur }}</span></div>
                </div>
            </div>
        </div>
    </div>
@endsection
