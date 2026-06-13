@extends('layouts.admin')

@section('title', 'Nouveau client')
@section('crumb', 'Nouveau client')

@section('content')
    <div class="page-head">
        <div class="page-titles">
            <h1>Nouveau client</h1>
            <p>Ajoutez manuellement un client à l'annuaire.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.clients.index') }}" class="btn btn-ghost"><x-icon name="arrow-left" size="16" /> Retour</a>
        </div>
    </div>

    @include('admin.clients._form')
@endsection
