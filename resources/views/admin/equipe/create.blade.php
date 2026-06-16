@extends('layouts.admin')

@section('title', __('Nouveau membre'))
@section('crumb', __('Nouveau membre'))

@section('content')
    <div class="page-head">
        <div class="page-titles">
            <h1>{{ __('Nouveau membre') }}</h1>
            <p>{{ __("Créez un compte d'accès au back-office (gérant ou opérateur).") }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.equipe.index') }}" class="btn btn-ghost"><x-icon name="arrow-left" size="16" /> {{ __('Retour') }}</a>
        </div>
    </div>

    @include('admin.equipe._form')
@endsection
