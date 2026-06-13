@extends('layouts.admin')

@section('title', 'Carte & plats')
@section('crumb', 'Carte & plats')

@section('content')
    <div class="page-head">
        <div class="page-titles">
            <h1>Carte &amp; plats</h1>
            <p>Gérez votre carte : ajoutez des plats, ajustez les prix ou marquez une rupture de stock.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.plats.create') }}" class="btn btn-primary"><x-icon name="plus" size="16" stroke="2.2" /> Nouveau plat</a>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.plats.index') }}" class="filters">
        <div class="search-box" style="height: 44px; flex: 1; min-width: 240px;">
            <x-icon name="search" size="19" />
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Rechercher un plat…">
        </div>
        <select name="categorie" class="select" style="max-width: 220px;" data-autosubmit-change>
            <option value="">Toutes les catégories</option>
            @foreach ($categories as $categorie)
                <option value="{{ $categorie->id }}" @selected(request('categorie') == $categorie->id)>{{ $categorie->nom }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-ghost">Filtrer</button>
    </form>

    @if ($plats->isEmpty())
        <div class="empty-state card">
            <span class="empty-ico"><x-icon name="utensils" size="28" /></span>
            <h3>Aucun plat</h3>
            <p>Commencez par ajouter un plat traditionnel à votre carte.</p>
            <a href="{{ route('admin.plats.create') }}" class="btn btn-primary">Ajouter un plat</a>
        </div>
    @else
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Plat</th><th>Catégorie</th><th>Prix</th><th>Stock</th><th>Disponibilité</th><th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($plats as $plat)
                        <tr>
                            <td>
                                <div class="cell-with-thumb">
                                    @if ($plat->image_url)
                                        <img src="{{ $plat->image_url }}" alt="" class="thumb">
                                    @else
                                        <span class="thumb thumb-fallback" style="display: grid;"><x-icon name="utensils" size="18" /></span>
                                    @endif
                                    <div>
                                        <div class="cell-strong">{{ $plat->nom }}</div>
                                        <div class="muted" style="font-size: 12px;">{{ $plat->temps_preparation }} min de préparation</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $plat->categorie->nom ?? '—' }}</td>
                            <td class="cell-strong nowrap">{{ number_format($plat->prix, 2, ',', ' ') }} DH</td>
                            <td>{{ $plat->stock }}</td>
                            <td>
                                @if ($plat->estEpuise())
                                    <span class="badge badge-red"><span class="dot"></span> Épuisé</span>
                                @else
                                    <span class="badge badge-ok"><span class="dot"></span> Disponible</span>
                                @endif
                            </td>
                            <td class="cell-actions">
                                <a href="{{ route('admin.plats.edit', $plat) }}" class="ghost-icon" aria-label="Modifier"><x-icon name="edit" size="17" /></a>
                                <form method="POST" action="{{ route('admin.plats.destroy', $plat) }}" data-confirm="Supprimer « {{ $plat->nom }} » ?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ghost-icon danger" aria-label="Supprimer"><x-icon name="trash" size="17" /></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pager">{{ $plats->links() }}</div>
    @endif
@endsection
