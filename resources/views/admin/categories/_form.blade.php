@php($estEdition = $categorie->exists)

<form method="POST"
      action="{{ $estEdition ? route('admin.categories.update', $categorie) : route('admin.categories.store') }}"
      class="form-card" style="max-width: 620px;">
    @csrf
    @if ($estEdition) @method('PUT') @endif

    <div class="stack" style="gap: 18px;">
        <div class="field">
            <label class="label">{{ __('Nom') }} <span class="req">*</span></label>
            <input type="text" name="nom" value="{{ old('nom', $categorie->nom) }}" class="input @error('nom') has-err @enderror" required placeholder="{{ __('Ex : Plats principaux, Spécialités de Fès…') }}">
            @error('nom') <span class="field-err">{{ $message }}</span> @enderror
        </div>

        <div class="field">
            <label class="label">{{ __('Description') }}</label>
            <textarea name="description" class="textarea @error('description') has-err @enderror" placeholder="{{ __('Courte description de la catégorie (facultatif)') }}">{{ old('description', $categorie->description) }}</textarea>
            @error('description') <span class="field-err">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="form-actions">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost">{{ __('Annuler') }}</a>
        <button type="submit" class="btn btn-primary">
            <x-icon name="check" size="16" stroke="2.2" /> {{ $estEdition ? __('Enregistrer') : __('Créer la catégorie') }}
        </button>
    </div>
</form>
