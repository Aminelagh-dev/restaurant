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

        // Le gérant peut revenir à n'importe quelle étape antérieure mais ne peut
        // avancer que d'un cran (statut suivant) ; l'opérateur ne peut que faire
        // avancer la commande au statut immédiatement suivant.
        if ($request->user()->isAdmin()) {
            if ($commande->positionStatut($data['statut']) > $commande->positionStatut() + 1) {
                abort(403, __('Action non autorisée.'));
            }
        } elseif ($data['statut'] !== $commande->statutSuivant()) {
            abort(403, __('Action non autorisée.'));
        }

        // On ne journalise une transition que si le statut change réellement,
        // pour éviter d'empiler des entrées d'historique identiques.
        if ($commande->statut !== $data['statut']) {
            $commande->changerStatut($data['statut']);
        }

        return back()->with('success', __('Statut mis à jour : :statut.', ['statut' => __($commande->statutLabel())]));
    }
}
