@php($estEdition = $plat->exists)

<form method="POST"
      action="{{ $estEdition ? route('admin.plats.update', $plat) : route('admin.plats.store') }}"
      enctype="multipart/form-data" class="form-card">
    @csrf
    @if ($estEdition) @method('PUT') @endif

    <div class="form-grid">
        <div class="field col-span-2">
            <label class="label">{{ __('Nom du plat') }} <span class="req">*</span></label>
            <input type="text" name="nom" value="{{ old('nom', $plat->nom) }}" class="input @error('nom') has-err @enderror" required placeholder="{{ __("Ex : Tagine d'agneau aux pruneaux") }}">
            @error('nom') <span class="field-err">{{ $message }}</span> @enderror
        </div>

        <div class="field">
            <label class="label">{{ __('Catégorie') }} <span class="req">*</span></label>
            <select name="categorie_id" class="select @error('categorie_id') has-err @enderror" required>
                <option value="">{{ __('— Choisir —') }}</option>
                @foreach ($categories as $categorie)
                    <option value="{{ $categorie->id }}" @selected(old('categorie_id', $plat->categorie_id) == $categorie->id)>{{ $categorie->nom }}</option>
                @endforeach
            </select>
            @error('categorie_id') <span class="field-err">{{ $message }}</span> @enderror
        </div>

        <div class="field">
            <label class="label">{{ __('Prix (DH)') }} <span class="req">*</span></label>
            <input type="number" step="0.01" min="0" name="prix" value="{{ old('prix', $plat->prix) }}" class="input @error('prix') has-err @enderror" required>
            @error('prix') <span class="field-err">{{ $message }}</span> @enderror
        </div>

        <div class="field">
            <label class="label">{{ __('Temps de préparation (min)') }} <span class="req">*</span></label>
            <input type="number" min="1" name="temps_preparation" value="{{ old('temps_preparation', $plat->temps_preparation) }}" class="input @error('temps_preparation') has-err @enderror" required>
            @error('temps_preparation') <span class="field-err">{{ $message }}</span> @enderror
        </div>

        <div class="field col-span-2">
            <label class="label">{{ __('Description') }} <span class="req">*</span></label>
            <textarea name="description" class="textarea @error('description') has-err @enderror" required placeholder="{{ __('Décrivez le plat, son origine, ses saveurs…') }}">{{ old('description', $plat->description) }}</textarea>
            @error('description') <span class="field-err">{{ $message }}</span> @enderror
        </div>

        <div class="field col-span-2">
            <label class="label">{{ __('Ingrédients principaux') }} <span class="req">*</span></label>
            <textarea name="ingredients" class="textarea @error('ingredients') has-err @enderror" required placeholder="{{ __('Séparez par des virgules : Agneau, Pruneaux, Amandes, Cannelle…') }}">{{ old('ingredients', $plat->ingredients) }}</textarea>
            <span class="field-hint">{{ __('Séparez les ingrédients par des virgules.') }}</span>
            @error('ingredients') <span class="field-err">{{ $message }}</span> @enderror
        </div>

        <div class="field">
            <label class="label">{{ __("URL de l'image") }}</label>
            <input type="text" name="image" value="{{ old('image', $plat->image) }}" class="input @error('image') has-err @enderror" placeholder="https://…">
            @error('image') <span class="field-err">{{ $message }}</span> @enderror
        </div>

        <div class="field">
            <label class="label">{{ __('…ou téléverser un fichier') }}</label>
            <input type="file" name="image_file" accept="image/*" class="input @error('image_file') has-err @enderror">
            @error('image_file') <span class="field-err">{{ $message }}</span> @enderror
        </div>

        @if ($plat->image_url)
            <div class="field">
                <label class="label">{{ __('Aperçu actuel') }}</label>
                <img src="{{ $plat->image_url }}" alt="" class="thumb" style="width: 80px; height: 80px;">
            </div>
        @endif

        <div class="field col-span-2">
            <div class="switch-field">
                <div>
                    <div class="label" style="margin: 0;">{{ __('Disponible à la commande') }}</div>
                    <div class="field-hint">{{ __('Désactivez pour marquer une rupture de stock.') }}</div>
                </div>
                <input type="checkbox" id="disponible" name="disponible" value="1" hidden
                       @checked(old('disponible', $plat->disponible ?? true))>
                <button type="button" class="toggle" data-toggle="disponible" aria-label="{{ __('Disponibilité') }}"><span class="toggle-knob"></span></button>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <a href="{{ route('admin.plats.index') }}" class="btn btn-ghost">{{ __('Annuler') }}</a>
        <button type="submit" class="btn btn-primary">
            <x-icon name="check" size="16" stroke="2.2" /> {{ $estEdition ? __('Enregistrer') : __('Ajouter le plat') }}
        </button>
    </div>
</form>
