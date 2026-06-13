@extends('layouts.admin')

@section('title', __('Modifier — :nom', ['nom' => $client->prenom.' '.$client->nom]))
@section('crumb', __('Modifier un client'))

@section('content')
    <div class="page-head">
        <div class="page-titles">
            <h1>{{ __('Modifier « :nom »', ['nom' => $client->prenom.' '.$client->nom]) }}</h1>
            <p>{{ __('Mettez à jour les coordonnées du client.') }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.clients.index') }}" class="btn btn-ghost"><x-icon name="arrow-left" size="16" /> {{ __('Retour') }}</a>
        </div>
    </div>

    @include('admin.clients._form')
@endsection
