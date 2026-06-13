@extends('layouts.admin')

@section('title', 'Catégories')
@section('crumb', 'Catégories')

@section('content')
    <div class="page-head">
        <div class="page-titles">
            <h1>Catégories</h1>
            <p>Organisez la carte par thématiques régionales (Fès, Marrakech…) ou par type de plat.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary"><x-icon name="plus" size="16" stroke="2.2" /> Nouvelle catégorie</a>
        </div>
    </div>

    @if ($categories->isEmpty())
        <div class="empty-state card">
            <span class="empty-ico"><x-icon name="layers" size="28" /></span>
            <h3>Aucune catégorie</h3>
            <p>Créez vos premières catégories pour classer les plats.</p>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Créer une catégorie</a>
        </div>
    @else
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr><th>Catégorie</th><th>Description</th><th>Plats</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach ($categories as $categorie)
                        <tr>
                            <td>
                                <div class="cell-with-thumb">
                                    <span class="thumb thumb-fallback" style="display: grid; background: var(--accent-soft); color: var(--accent);"><x-icon name="tag" size="18" /></span>
                                    <span class="cell-strong">{{ $categorie->nom }}</span>
                                </div>
                            </td>
                            <td class="muted">{{ \Illuminate\Support\Str::limit($categorie->description, 80) ?: '—' }}</td>
                            <td><span class="badge badge-neutral">{{ $categorie->plats_count }}</span></td>
                            <td class="cell-actions">
                                <a href="{{ route('admin.categories.edit', $categorie) }}" class="ghost-icon" aria-label="Modifier"><x-icon name="edit" size="17" /></a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $categorie) }}" data-confirm="Supprimer la catégorie « {{ $categorie->nom }} » ?">
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
    @endif
@endsection
