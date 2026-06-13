@extends('layouts.admin')

@section('title', __('Nouveau plat'))
@section('crumb', __('Nouveau plat'))

@section('content')
    <div class="page-head">
        <div class="page-titles">
            <h1>{{ __('Nouveau plat') }}</h1>
            <p>{{ __('Ajoutez un plat traditionnel à votre carte.') }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.plats.index') }}" class="btn btn-ghost"><x-icon name="arrow-left" size="16" /> {{ __('Retour à la carte') }}</a>
        </div>
    </div>

    @include('admin.plats._form')
@endsection
