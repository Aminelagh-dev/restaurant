@extends('layouts.admin')

@section('title', 'Nouvelle catégorie')
@section('crumb', 'Nouvelle catégorie')

@section('content')
    <div class="page-head">
        <div class="page-titles">
            <h1>Nouvelle catégorie</h1>
            <p>Créez une catégorie pour organiser votre carte.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost"><x-icon name="arrow-left" size="16" /> Retour</a>
        </div>
    </div>

    @include('admin.categories._form')
@endsection
