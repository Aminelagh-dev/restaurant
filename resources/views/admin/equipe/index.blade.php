@extends('layouts.admin')

@section('title', __('Équipe'))
@section('crumb', __('Équipe'))

@section('content')
    <div class="page-head">
        <div class="page-titles">
            <h1>{{ __('Équipe') }}</h1>
            <p>{{ __('Gérez les comptes gérants : création, modification, activation et désactivation.') }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.equipe.create') }}" class="btn btn-primary"><x-icon name="plus" size="16" stroke="2.2" /> {{ __('Nouveau gérant') }}</a>
        </div>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr><th>{{ __('Gérant') }}</th><th>{{ __('Email') }}</th><th>{{ __('Téléphone') }}</th><th>{{ __('Statut') }}</th><th></th></tr>
            </thead>
            <tbody>
                @foreach ($gerants as $gerant)
                    <tr>
                        <td>
                            <div class="cell-with-thumb">
                                <span class="avatar" style="border-radius: 11px;">{{ strtoupper(mb_substr($gerant->prenom ?: $gerant->nom, 0, 1).mb_substr($gerant->nom, 0, 1)) }}</span>
                                <span class="cell-strong">
                                    {{ trim($gerant->prenom.' '.$gerant->nom) }}
                                    @if ($gerant->is(auth()->user()))
                                        <span class="muted" style="font-weight: 500;">· {{ __('vous') }}</span>
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td class="nowrap">{{ $gerant->email }}</td>
                        <td class="muted">{{ $gerant->telephone ?: '—' }}</td>
                        <td>
                            @if ($gerant->actif)
                                <span class="badge badge-ok"><span class="dot"></span> {{ __('Actif') }}</span>
                            @else
                                <span class="badge badge-red"><span class="dot"></span> {{ __('Désactivé') }}</span>
                            @endif
                        </td>
                        <td class="cell-actions">
                            <a href="{{ route('admin.equipe.edit', $gerant) }}" class="ghost-icon" aria-label="{{ __('Modifier') }}"><x-icon name="edit" size="17" /></a>
                            @unless ($gerant->is(auth()->user()))
                                <form method="POST" action="{{ route('admin.equipe.statut', $gerant) }}"
                                      data-confirm="{{ $gerant->actif ? __('Désactiver :nom ?', ['nom' => trim($gerant->prenom.' '.$gerant->nom)]) : __('Réactiver :nom ?', ['nom' => trim($gerant->prenom.' '.$gerant->nom)]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="ghost-icon {{ $gerant->actif ? 'danger' : '' }}"
                                            aria-label="{{ $gerant->actif ? __('Désactiver') : __('Réactiver') }}"
                                            title="{{ $gerant->actif ? __('Désactiver') : __('Réactiver') }}">
                                        <x-icon name="{{ $gerant->actif ? 'x' : 'check' }}" size="17" stroke="2.2" />
                                    </button>
                                </form>
                            @endunless
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <p class="muted" style="margin: 12px 4px 0; font-size: 12.5px;">
        {{ __(':count gérant(s) · :actifs actif(s)', ['count' => $gerants->count(), 'actifs' => $actifs]) }}
    </p>
@endsection
