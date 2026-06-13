@extends('layouts.admin')

@section('title', __('Modifier — :nom', ['nom' => $plat->nom]))
@section('crumb', __('Modifier un plat'))

@section('content')
    <div class="page-head">
        <div class="page-titles">
            <h1>{{ __('Modifier « :nom »', ['nom' => $plat->nom]) }}</h1>
            <p>{{ __('Mettez à jour les informations, le prix ou la disponibilité du plat.') }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.plats.index') }}" class="btn btn-ghost"><x-icon name="arrow-left" size="16" /> {{ __('Retour à la carte') }}</a>
        </div>
    </div>

    @include('admin.plats._form')
@endsection
