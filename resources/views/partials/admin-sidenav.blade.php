@php
    $u = auth()->user();
    $isAdmin = $u->isAdmin();
    $enAttente = \App\Models\Commande::where('statut', \App\Models\Commande::STATUT_ATTENTE)->count();
@endphp

<aside class="sidenav" id="sidenav">
    <a href="{{ route($u->routeAccueilBackOffice()) }}" class="sidenav-brand">
        <span class="brand-mark"><x-icon name="sparkle" size="21" /></span>
        <span class="brand-text">
            <span class="brand-name">Riad Saveurs</span>
            <span class="brand-sub">{{ __('Console gérant') }}</span>
        </span>
    </a>

    <nav class="sidenav-scroll">
        @if ($isAdmin)
            <div class="nav-group">
                <div class="nav-group-label">{{ __('Pilotage') }}</div>
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-item {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" title="{{ __('Tableau de bord') }}">
                    <span class="nav-ico"><x-icon name="dashboard" size="19" /></span>
                    <span class="nav-label">{{ __('Tableau de bord') }}</span>
                </a>
            </div>

            <div class="nav-group">
                <div class="nav-group-label">{{ __('Catalogue') }}</div>
                <a href="{{ route('admin.plats.index') }}"
                   class="nav-item {{ request()->routeIs('admin.plats.*') ? 'is-active' : '' }}" title="{{ __('Carte') }}">
                    <span class="nav-ico"><x-icon name="utensils" size="19" /></span>
                    <span class="nav-label">{{ __('Carte & plats') }}</span>
                </a>
                <a href="{{ route('admin.categories.index') }}"
                   class="nav-item {{ request()->routeIs('admin.categories.*') ? 'is-active' : '' }}" title="{{ __('Catégories') }}">
                    <span class="nav-ico"><x-icon name="layers" size="19" /></span>
                    <span class="nav-label">{{ __('Catégories') }}</span>
                </a>
            </div>
        @endif

        <div class="nav-group">
            <div class="nav-group-label">{{ __('Activité') }}</div>
            <a href="{{ route('admin.commandes.index') }}"
               class="nav-item {{ request()->routeIs('admin.commandes.*') ? 'is-active' : '' }}" title="{{ __('Commandes') }}">
                <span class="nav-ico"><x-icon name="bag" size="19" /></span>
                <span class="nav-label">{{ __('Commandes') }}</span>
                @if ($enAttente > 0)
                    <span class="nav-badge">{{ $enAttente }}</span>
                @endif
            </a>
            @if ($isAdmin)
                <a href="{{ route('admin.clients.index') }}"
                   class="nav-item {{ request()->routeIs('admin.clients.*') ? 'is-active' : '' }}" title="{{ __('Clients') }}">
                    <span class="nav-ico"><x-icon name="users" size="19" /></span>
                    <span class="nav-label">{{ __('Clients') }}</span>
                </a>
            @endif
        </div>

        @if ($isAdmin)
            <div class="nav-group">
                <div class="nav-group-label">{{ __('Système') }}</div>
                <a href="{{ route('admin.equipe.index') }}"
                   class="nav-item {{ request()->routeIs('admin.equipe.*') ? 'is-active' : '' }}" title="{{ __('Équipe') }}">
                    <span class="nav-ico"><x-icon name="user" size="19" /></span>
                    <span class="nav-label">{{ __('Équipe') }}</span>
                </a>
            </div>
        @endif

        <div class="nav-group">
            <div class="nav-group-label">{{ __('Site') }}</div>
            <a href="{{ route('menu.index') }}" class="nav-item" title="{{ __('Voir le site') }}">
                <span class="nav-ico"><x-icon name="leaf" size="19" /></span>
                <span class="nav-label">{{ __('Voir le site client') }}</span>
            </a>
        </div>
    </nav>

    <div class="sidenav-user">
        <span class="avatar">{{ strtoupper(mb_substr($u->prenom ?: $u->nom, 0, 1).mb_substr($u->nom, 0, 1)) }}</span>
        <span class="user-meta">
            <span class="user-name">{{ trim($u->prenom.' '.$u->nom) }}</span>
            <span class="user-role">{{ __($u->roleLabel()) }}</span>
        </span>
        <button class="sidenav-pin" data-pin-sidenav type="button" aria-label="{{ __('Épingler le menu') }}" title="{{ __('Épingler le menu') }}">
            <x-icon name="pin" size="16" />
        </button>
    </div>
</aside>
