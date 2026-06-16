@extends('layouts.admin')

@section('title', __('Commande #:id', ['id' => $commande->id]))
@section('crumb', __('Commande #:id', ['id' => $commande->id]))

@section('content')
    <div class="page-head">
        <div class="page-titles">
            <h1>{{ __('Commande #:id', ['id' => $commande->id]) }}</h1>
            <p>{{ __('Reçue le :date', ['date' => $commande->date_commande->translatedFormat(__('d F Y à H:i'))]) }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.commandes.index') }}" class="btn btn-ghost"><x-icon name="arrow-left" size="16" /> {{ __('Retour') }}</a>
        </div>
    </div>

    <div class="cart-grid">
        <div class="card">
            <div class="card-pad" style="border-bottom: 1px solid var(--border-soft);">
                <h3 style="margin: 0; font-size: 15px; font-weight: 800;">{{ __('Plats commandés') }}</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr><th>{{ __('Plat') }}</th><th>{{ __('P.U.') }}</th><th>{{ __('Qté') }}</th><th class="text-right">{{ __('Sous-total') }}</th></tr>
                </thead>
                <tbody>
                    @foreach ($commande->lignes as $ligne)
                        <tr>
                            <td class="cell-strong">{{ $ligne->plat->nom ?? __('Plat supprimé') }}</td>
                            <td class="nowrap">{{ number_format($ligne->prix_unitaire, 2, ',', ' ') }} {{ __('DH') }}</td>
                            <td>{{ $ligne->quantite }}</td>
                            <td class="text-right cell-strong nowrap">{{ number_format($ligne->sous_total, 2, ',', ' ') }} {{ __('DH') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right cell-strong">{{ __('Total') }}</td>
                        <td class="text-right cell-strong nowrap" style="font-size: 16px;">{{ number_format($commande->montant_total, 2, ',', ' ') }} {{ __('DH') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="stack" style="gap: 18px;">
            <div class="card card-pad">
                <h3 style="margin: 0 0 14px; font-size: 15px; font-weight: 800;">{{ __('Statut') }}</h3>
                @if (auth()->user()->isAdmin())
                    <form method="POST" action="{{ route('admin.commandes.statut', $commande) }}" class="stack" style="gap: 12px;">
                        @csrf
                        @method('PATCH')
                        <select name="statut" class="select">
                            @foreach ($commande->statutsSelectionnables() as $cle => $libelle)
                                <option value="{{ $cle }}" @selected($commande->statut === $cle)>{{ __($libelle) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary btn-block"><x-icon name="check" size="16" stroke="2.2" /> {{ __('Mettre à jour le statut') }}</button>
                    </form>
                @else
                    {{-- Opérateur : statut courant + passage au statut suivant uniquement. --}}
                    <div class="stack" style="gap: 12px;">
                        <span class="badge badge-neutral" style="align-self: flex-start;"><span class="dot"></span> {{ __($commande->statutLabel()) }}</span>
                        @if ($commande->statutSuivant())
                            <form method="POST" action="{{ route('admin.commandes.statut', $commande) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="statut" value="{{ $commande->statutSuivant() }}">
                                <button type="submit" class="btn btn-primary btn-block"><x-icon name="check" size="16" stroke="2.2" /> {{ __('Marquer « :statut »', ['statut' => __($commande->statutSuivantLabel())]) }}</button>
                            </form>
                        @else
                            <p class="muted" style="margin: 0; font-size: 13px;">{{ __('Commande livrée — aucune action supplémentaire.') }}</p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="card card-pad">
                <h3 style="margin: 0 0 14px; font-size: 15px; font-weight: 800;">{{ __('Client & livraison') }}</h3>
                <div class="stack" style="gap: 11px; font-size: 13.5px; color: var(--ink-2);">
                    <div style="display: flex; gap: 9px;"><x-icon name="user" size="16" /> <span>{{ $commande->client?->prenom }} {{ $commande->client?->nom }}</span></div>
                    @if ($commande->client?->telephone)
                        <div style="display: flex; gap: 9px;"><x-icon name="phone" size="16" /> <span>{{ $commande->client->telephone }}</span></div>
                    @endif
                    <div style="display: flex; gap: 9px;"><x-icon name="map-pin" size="16" /> <span>{{ $commande->adresse_livraison }}</span></div>
                    <div style="display: flex; gap: 9px;"><x-icon name="truck" size="16" /> <span>{{ __('Destinataire : :nom · :tel', ['nom' => $commande->nom_recepteur, 'tel' => $commande->telephone_recepteur]) }}</span></div>
                </div>
            </div>
        </div>
    </div>
@endsection
