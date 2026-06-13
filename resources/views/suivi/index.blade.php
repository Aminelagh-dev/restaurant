@extends('layouts.app')

@section('title', __('Suivre ma commande'))

@section('content')
    <div style="max-width: 480px; margin: 30px auto;">
        <div class="empty-ico" style="margin: 0 auto 18px; background: var(--accent-soft); color: var(--accent);">
            <x-icon name="truck" size="28" />
        </div>
        <h1 style="text-align: center; margin: 0 0 8px; font-size: 26px; font-weight: 800; letter-spacing: -.02em;">{{ __('Suivre ma commande') }}</h1>
        <p class="muted" style="text-align: center; margin: 0 0 24px; font-size: 14px;">
            {{ __('Saisissez votre numéro de commande et le téléphone du destinataire.') }}
        </p>

        @error('numero') <div class="alert alert-err"><x-icon name="info" size="18" /> {{ $message }}</div> @enderror

        <form method="POST" action="{{ route('suivi.search') }}" class="form-card">
            @csrf
            <div class="stack" style="gap: 16px;">
                <div class="field">
                    <label class="label">{{ __('Numéro de commande') }} <span class="req">*</span></label>
                    <div class="input-group">
                        <input type="number" name="numero" value="{{ old('numero') }}" class="input" placeholder="{{ __('Ex : 1024') }}" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">{{ __('Téléphone du destinataire') }} <span class="req">*</span></label>
                    <input type="tel" name="telephone" value="{{ old('telephone') }}" class="input" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">
                    <x-icon name="search" size="17" /> {{ __('Rechercher ma commande') }}
                </button>
            </div>
        </form>
    </div>
@endsection
