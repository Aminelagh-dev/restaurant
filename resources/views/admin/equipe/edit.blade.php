@extends('layouts.admin')

@section('title', __('Modifier — :nom', ['nom' => trim($gerant->prenom.' '.$gerant->nom)]))
@section('crumb', __('Modifier un gérant'))

@section('content')
    <div class="page-head">
        <div class="page-titles">
            <h1>{{ __('Modifier « :nom »', ['nom' => trim($gerant->prenom.' '.$gerant->nom)]) }}</h1>
            <p>{{ __('Mettez à jour les informations ou le mot de passe du gérant.') }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.equipe.index') }}" class="btn btn-ghost"><x-icon name="arrow-left" size="16" /> {{ __('Retour') }}</a>
        </div>
    </div>

    @include('admin.equipe._form')
@endsection
