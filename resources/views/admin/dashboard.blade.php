@extends('layouts.admin')

@section('title', __('Tableau de bord'))
@section('crumb', __('Tableau de bord'))

@section('content')
    <div class="page-head">
        <div class="page-titles">
            <h1>{{ __('Tableau de bord') }}</h1>
            <p>{{ __("Vue d'ensemble de l'activité du restaurant — chiffre d'affaires et plats populaires.") }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.plats.create') }}" class="btn btn-primary"><x-icon name="plus" size="16" stroke="2.2" /> {{ __('Nouveau plat') }}</a>
        </div>
    </div>

    <div class="stat-grid">
        <div class="stat-card ink">
            <div class="stat-top">
                <span class="stat-lbl">{{ __("Chiffre d'affaires du jour") }}</span>
                <span class="stat-ico"><x-icon name="coins" size="20" /></span>
            </div>
            <div class="stat-val">{{ number_format($stats['ca_jour'], 2, ',', ' ') }} <small style="font-size: 14px;">{{ __('DH') }}</small></div>
            <div class="stat-lbl">{{ __('CA total cumulé :') }} {{ number_format($stats['ca_total'], 0, ',', ' ') }} {{ __('DH') }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <span class="stat-lbl">{{ __('Commandes') }}</span>
                <span class="stat-ico"><x-icon name="bag" size="20" /></span>
            </div>
            <div class="stat-val">{{ $stats['commandes'] }}</div>
            <div class="stat-lbl">{{ __(':count en préparation', ['count' => $parStatut[\App\Models\Commande::STATUT_PREPARATION] ?? 0]) }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <span class="stat-lbl">{{ __('Plats à la carte') }}</span>
                <span class="stat-ico"><x-icon name="utensils" size="20" /></span>
            </div>
            <div class="stat-val">{{ $stats['plats'] }}</div>
            <div class="stat-lbl">{{ __(':count épuisé(s)', ['count' => $stats['plats_epuises']]) }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <span class="stat-lbl">{{ __('Clients') }}</span>
                <span class="stat-ico"><x-icon name="users" size="20" /></span>
            </div>
            <div class="stat-val">{{ $stats['clients'] }}</div>
            <div class="stat-lbl">{{ __(':count catégories', ['count' => $stats['categories']]) }}</div>
        </div>
    </div>

    <div class="dash-grid">
        <div class="panel">
            <div class="panel-head">
                <h3>{{ __("Chiffre d'affaires — 7 derniers jours") }}</h3>
                <span class="badge badge-neutral">{{ __('DH') }}</span>
            </div>
            @php($maxCa = max(1, $ca7j->max('montant')))
            <div class="bars">
                @foreach ($ca7j as $jour)
                    <div class="bar-col">
                        <span class="bar-val">{{ $jour['montant'] > 0 ? number_format($jour['montant'], 0, ',', ' ') : '' }}</span>
                        <div class="bar" style="height: {{ $jour['montant'] > 0 ? max(4, round($jour['montant'] / $maxCa * 100)) : 2 }}%"></div>
                        <span class="bar-lbl">{{ $jour['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="panel">
            <div class="panel-head">
                <h3>{{ __('Plats les plus commandés') }}</h3>
                <x-icon name="fire" size="18" />
            </div>
            @if ($topPlats->isEmpty())
                <p class="muted" style="font-size: 13px;">{{ __("Aucune commande enregistrée pour l'instant.") }}</p>
            @else
                <div class="rank-list">
                    @foreach ($topPlats as $i => $plat)
                        <div class="rank-row">
                            <span class="rank-num">{{ $i + 1 }}</span>
                            <span class="rank-name">{{ $plat->nom }}</span>
                            <span class="rank-val">{{ __(':count vendus', ['count' => $plat->total_quantite]) }} · {{ number_format($plat->total_ca, 0, ',', ' ') }} {{ __('DH') }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="panel" style="margin-top: 18px;">
        <div class="panel-head">
            <h3>{{ __('Dernières commandes') }}</h3>
            <a href="{{ route('admin.commandes.index') }}" class="btn btn-ghost btn-sm">{{ __('Tout voir') }} <x-icon name="arrow-right" size="14" /></a>
        </div>
        @if ($recentes->isEmpty())
            <p class="muted" style="font-size: 13px;">{{ __('Aucune commande pour le moment.') }}</p>
        @else
            <div class="table-wrap" style="box-shadow: none; border-color: var(--border-soft);">
                <table class="data-table">
                    <thead>
                        <tr><th>#</th><th>{{ __('Client') }}</th><th>{{ __('Date') }}</th><th>{{ __('Montant') }}</th><th>{{ __('Statut') }}</th><th></th></tr>
                    </thead>
                    <tbody>
                        @foreach ($recentes as $commande)
                            <tr>
                                <td class="cell-strong mono">#{{ $commande->id }}</td>
                                <td>{{ $commande->client?->prenom }} {{ $commande->client?->nom }}</td>
                                <td class="muted">{{ $commande->date_commande->format('d/m/Y H:i') }}</td>
                                <td class="cell-strong nowrap">{{ number_format($commande->montant_total, 2, ',', ' ') }} {{ __('DH') }}</td>
                                <td>
                                    <span class="badge {{ $commande->statut === 'livree' ? 'badge-ok' : ($commande->statut === 'en_livraison' ? 'badge-amber' : 'badge-accent') }}">
                                        {{ __($commande->statutLabel()) }}
                                    </span>
                                </td>
                                <td class="cell-actions">
                                    <a href="{{ route('admin.commandes.show', $commande) }}" class="ghost-icon" aria-label="{{ __('Voir') }}"><x-icon name="eye" size="17" /></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
