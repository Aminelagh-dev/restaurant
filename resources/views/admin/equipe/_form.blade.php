@php($estEdition = $gerant->exists)

<form method="POST"
      action="{{ $estEdition ? route('admin.equipe.update', $gerant) : route('admin.equipe.store') }}"
      class="form-card" style="max-width: 720px;">
    @csrf
    @if ($estEdition) @method('PUT') @endif

    <div class="form-grid">
        <div class="field">
            <label class="label">{{ __('Prénom') }} <span class="req">*</span></label>
            <input type="text" name="prenom" value="{{ old('prenom', $gerant->prenom) }}" class="input @error('prenom') has-err @enderror" required>
            @error('prenom') <span class="field-err">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label class="label">{{ __('Nom') }} <span class="req">*</span></label>
            <input type="text" name="nom" value="{{ old('nom', $gerant->nom) }}" class="input @error('nom') has-err @enderror" required>
            @error('nom') <span class="field-err">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label class="label">{{ __('Adresse e-mail') }} <span class="req">*</span></label>
            <input type="email" name="email" value="{{ old('email', $gerant->email) }}" class="input @error('email') has-err @enderror" required autocomplete="off">
            @error('email') <span class="field-err">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label class="label">{{ __('Téléphone') }}</label>
            <input type="tel" name="telephone" value="{{ old('telephone', $gerant->telephone) }}" class="input @error('telephone') has-err @enderror">
            @error('telephone') <span class="field-err">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label class="label">{{ __('Rôle') }} <span class="req">*</span></label>
            <select name="role" class="select @error('role') has-err @enderror" required>
                @foreach (\App\Models\User::ROLES_BACK_OFFICE as $cle => $libelle)
                    <option value="{{ $cle }}" @selected(old('role', $gerant->role ?: \App\Models\User::ROLE_ADMIN) === $cle)>{{ __($libelle) }}</option>
                @endforeach
            </select>
            <span class="field-hint">{{ __('L’opérateur ne voit que les commandes et fait avancer leur statut.') }}</span>
            @error('role') <span class="field-err">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label class="label">{{ __('Mot de passe') }} @unless ($estEdition)<span class="req">*</span>@endunless</label>
            <input type="password" name="password" class="input @error('password') has-err @enderror" autocomplete="new-password" {{ $estEdition ? '' : 'required' }}>
            @if ($estEdition)
                <span class="field-hint">{{ __('Laisser vide pour conserver le mot de passe actuel.') }}</span>
            @endif
            @error('password') <span class="field-err">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label class="label">{{ __('Confirmer le mot de passe') }} @unless ($estEdition)<span class="req">*</span>@endunless</label>
            <input type="password" name="password_confirmation" class="input" autocomplete="new-password" {{ $estEdition ? '' : 'required' }}>
        </div>
    </div>

    <div class="form-actions">
        <a href="{{ route('admin.equipe.index') }}" class="btn btn-ghost">{{ __('Annuler') }}</a>
        <button type="submit" class="btn btn-primary">
            <x-icon name="check" size="16" stroke="2.2" /> {{ $estEdition ? __('Enregistrer') : __('Créer le gérant') }}
        </button>
    </div>
</form>
