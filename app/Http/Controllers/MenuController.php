<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Plats;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    /**
     * Menu gastronomique : plats classés par catégories.
     */
    public function index(Request $request): View
    {
        $recherche = trim((string) $request->query('q', ''));

        $categories = Categorie::query()
            ->with(['plats' => function ($query) use ($recherche) {
                $query->orderByDesc('disponible')->orderBy('nom');

                if ($recherche !== '') {
                    $query->where(function ($q) use ($recherche) {
                        $q->where('nom', 'like', "%{$recherche}%")
                            ->orWhere('description', 'like', "%{$recherche}%");
                    });
                }
            }])
            ->orderBy('nom')
            ->get()
            ->filter(fn (Categorie $categorie) => $categorie->plats->isNotEmpty());

        $total = $categories->sum(fn (Categorie $c) => $c->plats->count());

        return view('menu.index', compact('categories', 'recherche', 'total'));
    }

    /**
     * Détail d'un repas : ingrédients, temps de préparation, prix.
     */
    public function show(Plats $plat): View
    {
        $plat->load('categorie');

        $similaires = Plats::query()
            ->where('categorie_id', $plat->categorie_id)
            ->whereKeyNot($plat->id)
            ->orderByDesc('disponible')
            ->limit(3)
            ->get();

        return view('menu.show', compact('plat', 'similaires'));
    }
}
