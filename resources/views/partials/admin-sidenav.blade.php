@php
    $enPreparation = \App\Models\Commande::where('statut', \App\Models\Commande::STATUT_PREPARATION)->count();
@endphp

<aside class="sidenav" id="sidenav">
    <a href="{{ route('admin.dashboard') }}" class="sidenav-brand">
        <span class="brand-mark"><x-icon name="sparkle" size="21" /></span>
        <span class="brand-text">
            <span class="brand-name">Riad Saveurs</span>
            <span class="brand-sub">Console gérant</span>
        </span>
    </a>

    <nav class="sidenav-scroll">
        <div class="nav-group">
            <div class="nav-group-label">Pilotage</div>
            <a href="{{ route('admin.dashboard') }}"
               class="nav-item {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" title="Tableau de bord">
                <span class="nav-ico"><x-icon name="dashboard" size="19" /></span>
                <span class="nav-label">Tableau de bord</span>
            </a>
        </div>

        <div class="nav-group">
            <div class="nav-group-label">Catalogue</div>
            <a href="{{ route('admin.plats.index') }}"
               class="nav-item {{ request()->routeIs('admin.plats.*') ? 'is-active' : '' }}" title="Carte">
                <span class="nav-ico"><x-icon name="utensils" size="19" /></span>
                <span class="nav-label">Carte &amp; plats</span>
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="nav-item {{ request()->routeIs('admin.categories.*') ? 'is-active' : '' }}" title="Catégories">
                <span class="nav-ico"><x-icon name="layers" size="19" /></span>
                <span class="nav-label">Catégories</span>
            </a>
        </div>

        <div class="nav-group">
            <div class="nav-group-label">Activité</div>
            <a href="{{ route('admin.commandes.index') }}"
               class="nav-item {{ request()->routeIs('admin.commandes.*') ? 'is-active' : '' }}" title="Commandes">
                <span class="nav-ico"><x-icon name="bag" size="19" /></span>
                <span class="nav-label">Commandes</span>
                @if ($enPreparation > 0)
                    <span class="nav-badge">{{ $enPreparation }}</span>
                @endif
            </a>
            <a href="{{ route('admin.clients.index') }}"
               class="nav-item {{ request()->routeIs('admin.clients.*') ? 'is-active' : '' }}" title="Clients">
                <span class="nav-ico"><x-icon name="users" size="19" /></span>
                <span class="nav-label">Clients</span>
            </a>
        </div>

        <div class="nav-group">
            <div class="nav-group-label">Site</div>
            <a href="{{ route('menu.index') }}" class="nav-item" title="Voir le site">
                <span class="nav-ico"><x-icon name="leaf" size="19" /></span>
                <span class="nav-label">Voir le site client</span>
            </a>
        </div>
    </nav>

    <div class="sidenav-user">
        <span class="avatar">GR</span>
        <span class="user-meta">
            <span class="user-name">Gérant</span>
            <span class="user-role">admin</span>
        </span>
        <button class="sidenav-pin" data-pin-sidenav type="button" aria-label="Épingler le menu" title="Épingler le menu">
            <x-icon name="pin" size="16" />
        </button>
    </div>
</aside>
