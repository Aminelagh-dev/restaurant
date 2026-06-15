<?php

namespace App\Http\Controllers;

use App\Models\Plat;
use App\Support\Panier;
use Illuminate\Http\JsonResponse;
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
     *
     * Répond en JSON aux requêtes AJAX (mise à jour du compteur de panier sans
     * rechargement), et par une redirection classique sinon (fonctionne sans JS).
     */
    public function store(Request $request, Plat $plat): RedirectResponse|JsonResponse
    {
        if ($plat->estEpuise()) {
            $message = __('« :nom » est actuellement épuisé.', ['nom' => $plat->nom]);

            return $request->expectsJson()
                ? response()->json(['ok' => false, 'message' => $message, 'count' => Panier::count()], 422)
                : back()->with('error', $message);
        }

        $quantite = max(1, (int) $request->input('quantite', 1));
        Panier::add($plat->id, $quantite);

        $message = __('« :nom » a été ajouté au panier.', ['nom' => $plat->nom]);

        return $request->expectsJson()
            ? response()->json(['ok' => true, 'message' => $message, 'count' => Panier::count()])
            : back()->with('success', $message);
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
