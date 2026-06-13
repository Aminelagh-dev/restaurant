<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommandeController extends Controller
{
    /**
     * Liste chronologique des commandes, filtrable par statut.
     */
    public function index(Request $request): View
    {
        $statut = $request->query('statut');

        $commandes = Commande::query()
            ->with('client')
            ->withCount('lignes')
            ->when($statut && array_key_exists($statut, Commande::STATUTS), function ($query) use ($statut) {
                $query->where('statut', $statut);
            })
            ->latest('date_commande')
            ->paginate(15)
            ->withQueryString();

        return view('admin.commandes.index', [
            'commandes' => $commandes,
            'statuts' => Commande::STATUTS,
            'statutActif' => $statut,
        ]);
    }

    public function show(Commande $commande): View
    {
        $commande->load(['lignes.plat', 'client']);

        return view('admin.commandes.show', [
            'commande' => $commande,
            'statuts' => Commande::STATUTS,
        ]);
    }

    /**
     * Change le statut d'une commande en temps réel.
     */
    public function updateStatut(Request $request, Commande $commande): RedirectResponse
    {
        $data = $request->validate([
            'statut' => ['required', 'string', 'in:'.implode(',', array_keys(Commande::STATUTS))],
        ]);

        // statut n'est pas mass-assignable : affectation explicite après validation.
        $commande->statut = $data['statut'];
        $commande->save();

        return back()->with('success', 'Statut mis à jour : '.$commande->statutLabel().'.');
    }
}
