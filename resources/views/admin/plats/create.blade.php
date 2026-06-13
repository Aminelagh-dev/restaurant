@extends('layouts.admin')

@section('title', 'Nouveau plat')
@section('crumb', 'Nouveau plat')

@section('content')
    <div class="page-head">
        <div class="page-titles">
            <h1>Nouveau plat</h1>
            <p>Ajoutez un plat traditionnel à votre carte.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.plats.index') }}" class="btn btn-ghost"><x-icon name="arrow-left" size="16" /> Retour à la carte</a>
        </div>
    </div>

    @include('admin.plats._form')
@endsection
