@extends('layouts.auth')

@section('title', __('Connexion'))

@section('content')
    <div class="auth-card">
        <div class="auth-head">
            <span class="auth-mark"><x-icon name="dashboard" size="24" /></span>
            <h1>{{ __('Espace gérant') }}</h1>
            <p>{{ __('Connectez-vous pour accéder à la console de gestion.') }}</p>
        </div>

        <form method="POST" action="{{ route('admin.login.attempt') }}" class="stack" style="gap: 16px;">
            @csrf

            <div class="field">
                <label class="label">{{ __('Adresse e-mail') }}</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="input @error('email') has-err @enderror"
                       required autofocus autocomplete="username">
                @error('email') <span class="field-err">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label class="label">{{ __('Mot de passe') }}</label>
                <input type="password" name="password"
                       class="input @error('password') has-err @enderror"
                       required autocomplete="current-password">
                @error('password') <span class="field-err">{{ $message }}</span> @enderror
            </div>

            <label class="auth-remember">
                <input type="checkbox" name="remember" value="1">
                <span>{{ __('Se souvenir de moi') }}</span>
            </label>

            <button type="submit" class="btn btn-primary btn-block">
                {{ __('Se connecter') }} <x-icon name="arrow-right" size="16" stroke="2" />
            </button>
        </form>

        <a href="{{ route('menu.index') }}" class="auth-back">
            <x-icon name="arrow-left" size="15" /> {{ __('Retour au site') }}
        </a>
    </div>
@endsection
