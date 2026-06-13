@extends('layouts.app')

@section('title', 'Passer commande')

@section('content')
    <div class="page-head">
        <div class="page-titles">
            <h1>Finaliser ma commande</h1>
            <p>Renseignez l'adresse de livraison et les informations du destinataire.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('panier.index') }}" class="btn btn-ghost"><x-icon name="arrow-left" size="16" /> Retour au panier</a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-err">
            <x-icon name="info" size="18" />
            <div>
                Merci de corriger les champs suivants :
                <ul>
                    @foreach ($errors->all() as $erreur)
                        <li>{{ $erreur }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('checkout.store') }}">
        @csrf
        <div class="cart-grid">
            <div class="stack" style="gap: 18px;">
                <div class="form-card">
                    <h3 style="margin: 0 0 18px; font-size: 16px; font-weight: 800;">Vos coordonnées</h3>
                    <div class="form-grid">
                        <div class="field">
                            <label class="label">Prénom <span class="req">*</span></label>
                            <input type="text" name="prenom" value="{{ old('prenom') }}" class="input @error('prenom') has-err @enderror" required>
                            @error('prenom') <span class="field-err">{{ $message }}</span> @enderror
                        </div>
                        <div class="field">
                            <label class="label">Nom <span class="req">*</span></label>
                            <input type="text" name="nom" value="{{ old('nom') }}" class="input @error('nom') has-err @enderror" required>
                            @error('nom') <span class="field-err">{{ $message }}</span> @enderror
                        </div>
                        <div class="field">
                            <label class="label">Téléphone <span class="req">*</span></label>
                            <input type="tel" name="telephone" value="{{ old('telephone') }}" class="input @error('telephone') has-err @enderror" required>
                            @error('telephone') <span class="field-err">{{ $message }}</span> @enderror
                        </div>
                        <div class="field">
                            <label class="label">Email (optionnel)</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="input @error('email') has-err @enderror">
                            @error('email') <span class="field-err">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="form-card">
                    <h3 style="margin: 0 0 18px; font-size: 16px; font-weight: 800;">Livraison</h3>
                    <div class="form-grid">
                        <div class="field col-span-2">
                            <label class="label">Adresse de livraison <span class="req">*</span></label>
                            <textarea name="adresse_livraison" class="textarea @error('adresse_livraison') has-err @enderror" required placeholder="N°, rue, quartier, ville…">{{ old('adresse_livraison') }}</textarea>
                            @error('adresse_livraison') <span class="field-err">{{ $message }}</span> @enderror
                        </div>
                        <div class="field">
                            <label class="label">Nom du destinataire <span class="req">*</span></label>
                            <input type="text" name="nom_recepteur" value="{{ old('nom_recepteur') }}" class="input @error('nom_recepteur') has-err @enderror" required>
                            @error('nom_recepteur') <span class="field-err">{{ $message }}</span> @enderror
                        </div>
                        <div class="field">
                            <label class="label">Téléphone du destinataire <span class="req">*</span></label>
                            <input type="tel" name="telephone_recepteur" value="{{ old('telephone_recepteur') }}" class="input @error('telephone_recepteur') has-err @enderror" required>
                            @error('telephone_recepteur') <span class="field-err">{{ $message }}</span> @enderror
                            <span class="field-hint">Servira aussi à suivre la commande.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="summary">
                <div class="card card-pad">
                    <h3 style="margin: 0 0 14px; font-size: 16px; font-weight: 800;">Votre commande</h3>
                    @foreach ($lignes as $ligne)
                        <div class="summary-row">
                            <span>{{ $ligne['quantite'] }} × {{ $ligne['plat']->nom }}</span>
                            <span class="nowrap">{{ number_format($ligne['sous_total'], 2, ',', ' ') }} DH</span>
                        </div>
                    @endforeach
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>{{ number_format($total, 2, ',', ' ') }} DH</span>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" style="margin-top: 16px;">
                        <x-icon name="check" size="17" stroke="2.2" /> Confirmer la commande
                    </button>
                    <p class="muted" style="font-size: 11.5px; text-align: center; margin: 12px 0 0;">
                        Paiement à la livraison · Livraison offerte
                    </p>
                </div>
            </div>
        </div>
    </form>
@endsection
