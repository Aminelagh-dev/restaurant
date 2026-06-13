<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuiviController extends Controller
{
    /**
     * Clé de session listant les commandes que le visiteur est autorisé à suivre
     * (renseignée après un paiement ou une recherche réussie).
     */
    private const AUTORISEES = 'suivi_autorisees';

    /**
     * Formulaire de recherche d'une commande à suivre.
     */
    public function index(): View
    {
        return view('suivi.index');
    }

    /**
     * Recherche une commande par numéro + téléphone du destinataire.
     */
    public function search(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'numero' => ['required', 'integer'],
            'telephone' => ['required', 'string'],
        ], [], [
            'numero' => __('numéro de commande'),
            'telephone' => __('téléphone'),
        ]);

        $commande = Commande::query()
            ->where('id', $data['numero'])
            ->where('telephone_recepteur', $data['telephone'])
            ->first();

        if (! $commande) {
            return back()
                ->withInput()
                ->with('error', __('Aucune commande ne correspond à ces informations.'));
        }

        self::autoriser($commande);

        return redirect()->route('suivi.show', $commande);
    }

    /**
     * Affiche le statut détaillé d'une commande (réservé aux commandes
     * dont l'accès a été validé par paiement ou recherche).
     */
    public function show(Request $request, Commande $commande): View
    {
        abort_unless(
            in_array($commande->id, $request->session()->get(self::AUTORISEES, []), true),
            403,
            __('Accès non autorisé à cette commande. Recherchez-la via le numéro et le téléphone du destinataire.')
        );

        $commande->load(['lignes.plat', 'client', 'historiqueStatuts']);

        return view('suivi.show', [
            'commande' => $commande,
            'statuts' => Commande::STATUTS,
            // Date de la première occurrence de chaque statut atteint, pour
            // horodater les étapes de la frise de suivi.
            'datesStatuts' => $commande->historiqueStatuts
                ->groupBy('statut')
                ->map(fn ($entrees) => $entrees->first()->date_action),
        ]);
    }

    /**
     * Marque une commande comme consultable par le visiteur courant.
     */
    public static function autoriser(Commande $commande): void
    {
        $autorisees = session()->get(self::AUTORISEES, []);
        $autorisees[] = $commande->id;
        session()->put(self::AUTORISEES, array_values(array_unique($autorisees)));
    }
}
