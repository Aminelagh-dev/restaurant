@extends('layouts.admin')

@section('title', __('Nouveau gérant'))
@section('crumb', __('Nouveau gérant'))

@section('content')
    <div class="page-head">
        <div class="page-titles">
            <h1>{{ __('Nouveau gérant') }}</h1>
            <p>{{ __("Créez un compte d'accès au back-office.") }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.equipe.index') }}" class="btn btn-ghost"><x-icon name="arrow-left" size="16" /> {{ __('Retour') }}</a>
        </div>
    </div>

    @include('admin.equipe._form')
@endsection
