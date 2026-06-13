@extends('layouts.admin')

@section('title', __('Commandes'))
@section('crumb', __('Commandes'))

@section('content')
    <div class="page-head">
        <div class="page-titles">
            <h1>{{ __('Commandes') }}</h1>
            <p>{{ __('Liste chronologique des commandes reçues. Changez le statut en temps réel.') }}</p>
        </div>
    </div>

    <div class="filters">
        <a href="{{ route('admin.commandes.index') }}" class="chip-filter {{ ! $statutActif ? 'is-active' : '' }}">{{ __('Toutes') }}</a>
        @foreach ($statuts as $cle => $libelle)
            <a href="{{ route('admin.commandes.index', ['statut' => $cle]) }}"
               class="chip-filter {{ $statutActif === $cle ? 'is-active' : '' }}">{{ __($libelle) }}</a>
        @endforeach
    </div>

    @if ($commandes->isEmpty())
        <div class="empty-state card">
            <span class="empty-ico"><x-icon name="bag" size="28" /></span>
            <h3>{{ __('Aucune commande') }}</h3>
            <p>{{ __('Les commandes passées par les clients apparaîtront ici.') }}</p>
        </div>
    @else
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr><th>#</th><th>{{ __('Client') }}</th><th>{{ __('Date') }}</th><th>{{ __('Articles') }}</th><th>{{ __('Montant') }}</th><th>{{ __('Statut') }}</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach ($commandes as $commande)
                        <tr>
                            <td class="cell-strong mono">#{{ $commande->id }}</td>
                            <td>
                                <div class="cell-strong">{{ $commande->client?->prenom }} {{ $commande->client?->nom }}</div>
                                <div class="muted" style="font-size: 12px;">{{ $commande->nom_recepteur }}</div>
                            </td>
                            <td class="muted nowrap">{{ $commande->date_commande->format('d/m/Y H:i') }}</td>
                            <td>{{ $commande->lignes_count }}</td>
                            <td class="cell-strong nowrap">{{ number_format($commande->montant_total, 2, ',', ' ') }} {{ __('DH') }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.commandes.statut', $commande) }}">
                                    @csrf
                                    @method('PATCH')
                                    <select name="statut" class="select" style="padding: 7px 10px; font-size: 12.5px; min-width: 165px;" data-autosubmit-change>
                                        @foreach ($statuts as $cle => $libelle)
                                            <option value="{{ $cle }}" @selected($commande->statut === $cle)>{{ __($libelle) }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td class="cell-actions">
                                <a href="{{ route('admin.commandes.show', $commande) }}" class="ghost-icon" aria-label="{{ __('Détail') }}"><x-icon name="eye" size="17" /></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pager">{{ $commandes->links() }}</div>
    @endif
@endsection
