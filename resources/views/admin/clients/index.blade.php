@extends('layouts.admin')

@section('title', 'Clients')
@section('crumb', 'Clients')

@section('content')
    <div class="page-head">
        <div class="page-titles">
            <h1>Clients</h1>
            <p>Annuaire des clients ayant passé commande.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.clients.create') }}" class="btn btn-primary"><x-icon name="plus" size="16" stroke="2.2" /> Nouveau client</a>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.clients.index') }}" class="filters">
        <div class="search-box" style="height: 44px; flex: 1; min-width: 240px;">
            <x-icon name="search" size="19" />
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Rechercher (nom, téléphone, email)…">
        </div>
        <button type="submit" class="btn btn-ghost">Rechercher</button>
    </form>

    @if ($clients->isEmpty())
        <div class="empty-state card">
            <span class="empty-ico"><x-icon name="users" size="28" /></span>
            <h3>Aucun client</h3>
            <p>Les clients apparaîtront ici après leur première commande.</p>
            <a href="{{ route('admin.clients.create') }}" class="btn btn-primary">Ajouter un client</a>
        </div>
    @else
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr><th>Client</th><th>Téléphone</th><th>Email</th><th>Commandes</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach ($clients as $client)
                        <tr>
                            <td>
                                <div class="cell-with-thumb">
                                    <span class="avatar" style="border-radius: 11px;">{{ strtoupper(mb_substr($client->prenom, 0, 1) . mb_substr($client->nom, 0, 1)) }}</span>
                                    <span class="cell-strong">{{ $client->prenom }} {{ $client->nom }}</span>
                                </div>
                            </td>
                            <td class="nowrap">{{ $client->telephone }}</td>
                            <td class="muted">{{ $client->email ?: '—' }}</td>
                            <td><span class="badge badge-neutral">{{ $client->commandes_count }}</span></td>
                            <td class="cell-actions">
                                <a href="{{ route('admin.clients.edit', $client) }}" class="ghost-icon" aria-label="Modifier"><x-icon name="edit" size="17" /></a>
                                <form method="POST" action="{{ route('admin.clients.destroy', $client) }}" data-confirm="Supprimer {{ $client->prenom }} {{ $client->nom }} et ses commandes ?">
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
        <div class="pager">{{ $clients->links() }}</div>
    @endif
@endsection
