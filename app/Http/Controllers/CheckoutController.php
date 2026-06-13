<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Client;
use App\Models\Commande;
use App\Support\Panier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Formulaire de passage de commande (adresse + destinataire).
     */
    public function create(): View|RedirectResponse
    {
        if (Panier::isEmpty()) {
            return redirect()->route('menu.index')
                ->with('error', 'Votre panier est vide.');
        }

        return view('checkout.create', [
            'lignes' => Panier::lignes(),
            'total' => Panier::total(),
        ]);
    }

    /**
     * Valide et enregistre la commande.
     */
    public function store(CheckoutRequest $request): RedirectResponse
    {
        $lignes = Panier::lignes();

        if ($lignes->isEmpty()) {
            return redirect()->route('menu.index')
                ->with('error', 'Votre panier est vide.');
        }

        // Vérifie la disponibilité avant d'enregistrer.
        $indisponible = $lignes->first(fn ($l) => $l['plat']->estEpuise());
        if ($indisponible) {
            return redirect()->route('panier.index')
                ->with('error', "« {$indisponible['plat']->nom} » n'est plus disponible.");
        }

        $data = $request->validated();

        $commande = DB::transaction(function () use ($data, $lignes) {
            $client = Client::firstOrCreate(
                ['telephone' => $data['telephone']],
                [
                    'nom' => $data['nom'],
                    'prenom' => $data['prenom'],
                    'email' => $data['email'] ?? null,
                ]
            );

            $commande = new Commande([
                'client_id' => $client->id,
                'date_commande' => now(),
                'montant_total' => $lignes->sum('sous_total'),
                'adresse_livraison' => $data['adresse_livraison'],
                'nom_recepteur' => $data['nom_recepteur'],
                'telephone_recepteur' => $data['telephone_recepteur'],
            ]);
            // statut n'est pas mass-assignable : on le fixe explicitement.
            $commande->statut = Commande::STATUT_PREPARATION;
            $commande->save();

            foreach ($lignes as $ligne) {
                $plat = $ligne['plat'];

                $commande->lignes()->create([
                    'plat_id' => $plat->id,
                    'quantite' => $ligne['quantite'],
                    'prix_unitaire' => $plat->prix,
                    'sous_total' => $ligne['sous_total'],
                ]);

                // Décrémente le stock et marque en rupture si épuisé.
                $plat->decrement('stock', $ligne['quantite']);
                if ($plat->fresh()->stock <= 0) {
                    $plat->update(['disponible' => false]);
                }
            }

            return $commande;
        });

        Panier::clear();
        SuiviController::autoriser($commande);

        return redirect()->route('suivi.show', $commande)
            ->with('success', 'Votre commande a bien été enregistrée. Vous pouvez suivre son statut ci-dessous.');
    }
}
