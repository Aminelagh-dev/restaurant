@php($estEdition = $client->exists)

<form method="POST"
      action="{{ $estEdition ? route('admin.clients.update', $client) : route('admin.clients.store') }}"
      class="form-card" style="max-width: 720px;">
    @csrf
    @if ($estEdition) @method('PUT') @endif

    <div class="form-grid">
        <div class="field">
            <label class="label">Prénom <span class="req">*</span></label>
            <input type="text" name="prenom" value="{{ old('prenom', $client->prenom) }}" class="input @error('prenom') has-err @enderror" required>
            @error('prenom') <span class="field-err">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label class="label">Nom <span class="req">*</span></label>
            <input type="text" name="nom" value="{{ old('nom', $client->nom) }}" class="input @error('nom') has-err @enderror" required>
            @error('nom') <span class="field-err">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label class="label">Téléphone <span class="req">*</span></label>
            <input type="tel" name="telephone" value="{{ old('telephone', $client->telephone) }}" class="input @error('telephone') has-err @enderror" required>
            @error('telephone') <span class="field-err">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label class="label">Email</label>
            <input type="email" name="email" value="{{ old('email', $client->email) }}" class="input @error('email') has-err @enderror">
            @error('email') <span class="field-err">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="form-actions">
        <a href="{{ route('admin.clients.index') }}" class="btn btn-ghost">Annuler</a>
        <button type="submit" class="btn btn-primary">
            <x-icon name="check" size="16" stroke="2.2" /> {{ $estEdition ? 'Enregistrer' : 'Créer le client' }}
        </button>
    </div>
</form>
