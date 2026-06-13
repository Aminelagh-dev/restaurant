@extends('layouts.admin')

@section('title', __('Clients'))
@section('crumb', __('Clients'))

@section('content')
    <div class="page-head">
        <div class="page-titles">
            <h1>{{ __('Clients') }}</h1>
            <p>{{ __('Annuaire des clients ayant passé commande.') }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.clients.create') }}" class="btn btn-primary"><x-icon name="plus" size="16" stroke="2.2" /> {{ __('Nouveau client') }}</a>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.clients.index') }}" class="filters">
        <div class="search-box" style="height: 44px; flex: 1; min-width: 240px;">
            <x-icon name="search" size="19" />
            <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('Rechercher (nom, téléphone, email)…') }}">
        </div>
        <button type="submit" class="btn btn-ghost">{{ __('Rechercher') }}</button>
    </form>

    @if ($clients->isEmpty())
        <div class="empty-state card">
            <span class="empty-ico"><x-icon name="users" size="28" /></span>
            <h3>{{ __('Aucun client') }}</h3>
            <p>{{ __('Les clients apparaîtront ici après leur première commande.') }}</p>
            <a href="{{ route('admin.clients.create') }}" class="btn btn-primary">{{ __('Ajouter un client') }}</a>
        </div>
    @else
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr><th>{{ __('Client') }}</th><th>{{ __('Téléphone') }}</th><th>{{ __('Email') }}</th><th>{{ __('Commandes') }}</th><th></th></tr>
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
                                <a href="{{ route('admin.clients.edit', $client) }}" class="ghost-icon" aria-label="{{ __('Modifier') }}"><x-icon name="edit" size="17" /></a>
                                <form method="POST" action="{{ route('admin.clients.destroy', $client) }}" data-confirm="{{ __('Supprimer :nom et ses commandes ?', ['nom' => $client->prenom.' '.$client->nom]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ghost-icon danger" aria-label="{{ __('Supprimer') }}"><x-icon name="trash" size="17" /></button>
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
