<?php

namespace App\Http\Controllers;

use App\Models\Plat;
use App\Support\Panier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PanierController extends Controller
{
    /**
     * Affiche le panier (plats choisis, quantités, total).
     */
    public function index(): View
    {
        return view('panier.index', [
            'lignes' => Panier::lignes(),
            'total' => Panier::total(),
        ]);
    }

    /**
     * Ajoute un plat au panier.
     */
    public function store(Request $request, Plat $plat): RedirectResponse
    {
        if ($plat->estEpuise()) {
            return back()->with('error', __('« :nom » est actuellement épuisé.', ['nom' => $plat->nom]));
        }

        $quantite = max(1, (int) $request->input('quantite', 1));
        Panier::add($plat->id, $quantite);

        return back()->with('success', __('« :nom » a été ajouté au panier.', ['nom' => $plat->nom]));
    }

    /**
     * Met à jour la quantité d'un plat dans le panier.
     */
    public function update(Request $request, Plat $plat): RedirectResponse
    {
        $quantite = (int) $request->input('quantite', 1);
        Panier::set($plat->id, $quantite);

        return back()->with('success', __('Panier mis à jour.'));
    }

    /**
     * Retire un plat du panier.
     */
    public function destroy(Plat $plat): RedirectResponse
    {
        Panier::remove($plat->id);

        return back()->with('success', __('Plat retiré du panier.'));
    }

    /**
     * Vide entièrement le panier.
     */
    public function clear(): RedirectResponse
    {
        Panier::clear();

        return back()->with('success', __('Le panier a été vidé.'));
    }
}
