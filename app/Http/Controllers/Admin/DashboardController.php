<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Client;
use App\Models\Commande;
use App\Models\Plats;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tableau de bord : plats populaires + chiffre d'affaires.
     */
    public function index(): View
    {
        $stats = [
            'plats' => Plats::count(),
            'plats_epuises' => Plats::where('disponible', false)->orWhere('stock', '<=', 0)->count(),
            'categories' => Categorie::count(),
            'clients' => Client::count(),
            'commandes' => Commande::count(),
            'ca_jour' => (float) Commande::whereDate('date_commande', today())->sum('montant_total'),
            'ca_total' => (float) Commande::sum('montant_total'),
        ];

        // Répartition des commandes par statut.
        $parStatut = Commande::query()
            ->selectRaw('statut, COUNT(*) as total')
            ->groupBy('statut')
            ->pluck('total', 'statut');

        // Plats les plus commandés.
        $topPlats = DB::table('commande_plat')
            ->join('plats', 'plats.id', '=', 'commande_plat.plat_id')
            ->select(
                'plats.nom',
                DB::raw('SUM(commande_plat.quantite) as total_quantite'),
                DB::raw('SUM(commande_plat.sous_total) as total_ca')
            )
            ->groupBy('plats.id', 'plats.nom')
            ->orderByDesc('total_quantite')
            ->limit(5)
            ->get();

        // Chiffre d'affaires des 7 derniers jours.
        $ventes = Commande::query()
            ->where('date_commande', '>=', now()->subDays(6)->startOfDay())
            ->get(['date_commande', 'montant_total'])
            ->groupBy(fn (Commande $c) => $c->date_commande->format('Y-m-d'))
            ->map(fn ($jour) => (float) $jour->sum('montant_total'));

        $ca7j = collect(range(6, 0))->map(function ($offset) use ($ventes) {
            $date = now()->subDays($offset);

            return [
                'label' => $date->isoFormat('dd D'),
                'montant' => $ventes->get($date->format('Y-m-d'), 0.0),
            ];
        });

        $recentes = Commande::with('client')
            ->latest('date_commande')
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'parStatut', 'topPlats', 'ca7j', 'recentes'));
    }
}
