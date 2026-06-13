@extends('layouts.admin')

@section('title', __('Nouveau client'))
@section('crumb', __('Nouveau client'))

@section('content')
    <div class="page-head">
        <div class="page-titles">
            <h1>{{ __('Nouveau client') }}</h1>
            <p>{{ __("Ajoutez manuellement un client à l'annuaire.") }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.clients.index') }}" class="btn btn-ghost"><x-icon name="arrow-left" size="16" /> {{ __('Retour') }}</a>
        </div>
    </div>

    @include('admin.clients._form')
@endsection
