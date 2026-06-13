@extends('layouts.admin')

@section('title', 'Modifier — ' . $categorie->nom)
@section('crumb', 'Modifier une catégorie')

@section('content')
    <div class="page-head">
        <div class="page-titles">
            <h1>Modifier « {{ $categorie->nom }} »</h1>
            <p>Mettez à jour le nom ou la description de la catégorie.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost"><x-icon name="arrow-left" size="16" /> Retour</a>
        </div>
    </div>

    @include('admin.categories._form')
@endsection
