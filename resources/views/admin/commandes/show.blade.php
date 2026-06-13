@extends('layouts.admin')

@section('title', 'Commande #' . $commande->id)
@section('crumb', 'Commande #' . $commande->id)

@section('content')
    <div class="page-head">
        <div class="page-titles">
            <h1>Commande #{{ $commande->id }}</h1>
            <p>Reçue le {{ $commande->date_commande->translatedFormat('d F Y à H:i') }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.commandes.index') }}" class="btn btn-ghost"><x-icon name="arrow-left" size="16" /> Retour</a>
        </div>
    </div>

    <div class="cart-grid">
        <div class="card">
            <div class="card-pad" style="border-bottom: 1px solid var(--border-soft);">
                <h3 style="margin: 0; font-size: 15px; font-weight: 800;">Plats commandés</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr><th>Plat</th><th>P.U.</th><th>Qté</th><th class="text-right">Sous-total</th></tr>
                </thead>
                <tbody>
                    @foreach ($commande->lignes as $ligne)
                        <tr>
                            <td class="cell-strong">{{ $ligne->plat->nom ?? 'Plat supprimé' }}</td>
                            <td class="nowrap">{{ number_format($ligne->prix_unitaire, 2, ',', ' ') }} DH</td>
                            <td>{{ $ligne->quantite }}</td>
                            <td class="text-right cell-strong nowrap">{{ number_format($ligne->sous_total, 2, ',', ' ') }} DH</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right cell-strong">Total</td>
                        <td class="text-right cell-strong nowrap" style="font-size: 16px;">{{ number_format($commande->montant_total, 2, ',', ' ') }} DH</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="stack" style="gap: 18px;">
            <div class="card card-pad">
                <h3 style="margin: 0 0 14px; font-size: 15px; font-weight: 800;">Statut</h3>
                <form method="POST" action="{{ route('admin.commandes.statut', $commande) }}" class="stack" style="gap: 12px;">
                    @csrf
                    @method('PATCH')
                    <select name="statut" class="select">
                        @foreach ($statuts as $cle => $libelle)
                            <option value="{{ $cle }}" @selected($commande->statut === $cle)>{{ $libelle }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary btn-block"><x-icon name="check" size="16" stroke="2.2" /> Mettre à jour le statut</button>
                </form>
            </div>

            <div class="card card-pad">
                <h3 style="margin: 0 0 14px; font-size: 15px; font-weight: 800;">Client &amp; livraison</h3>
                <div class="stack" style="gap: 11px; font-size: 13.5px; color: var(--ink-2);">
                    <div style="display: flex; gap: 9px;"><x-icon name="user" size="16" /> <span>{{ $commande->client?->prenom }} {{ $commande->client?->nom }}</span></div>
                    @if ($commande->client?->telephone)
                        <div style="display: flex; gap: 9px;"><x-icon name="phone" size="16" /> <span>{{ $commande->client->telephone }}</span></div>
                    @endif
                    <div style="display: flex; gap: 9px;"><x-icon name="map-pin" size="16" /> <span>{{ $commande->adresse_livraison }}</span></div>
                    <div style="display: flex; gap: 9px;"><x-icon name="truck" size="16" /> <span>Destinataire : {{ $commande->nom_recepteur }} · {{ $commande->telephone_recepteur }}</span></div>
                </div>
            </div>
        </div>
    </div>
@endsection
