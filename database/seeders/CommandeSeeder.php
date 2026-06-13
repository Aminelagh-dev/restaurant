<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Commande;
use App\Models\Plats;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CommandeSeeder extends Seeder
{
    /**
     * Clients de démonstration + commandes réparties sur 7 jours.
     */
    public function run(): void
    {
        $plats = Plats::all();
        if ($plats->isEmpty()) {
            return;
        }

        // Idempotence : on ne re-génère pas de commandes de démonstration si la
        // base en contient déjà (évite d'empiler des commandes à chaque seed).
        if (Commande::query()->exists()) {
            return;
        }

        $clients = [
            ['Bennani', 'Yasmine', '0612345678', 'yasmine.bennani@example.ma'],
            ['El Idrissi', 'Mehdi', '0623456789', 'mehdi.idrissi@example.ma'],
            ['Alaoui', 'Salma', '0634567890', null],
            ['Tazi', 'Karim', '0645678901', 'karim.tazi@example.ma'],
            ['Cherkaoui', 'Nadia', '0656789012', 'nadia.cherkaoui@example.ma'],
        ];

        $statuts = array_keys(Commande::STATUTS);
        $adresses = [
            'Rue de la Liberté, Guéliz, Marrakech',
            'Avenue Mohammed V, Fès',
            'Quartier Habous, Casablanca',
            'Rue Souika, Rabat',
            'Boulevard Zerktouni, Casablanca',
        ];

        foreach ($clients as $i => [$nom, $prenom, $tel, $email]) {
            // Le client est identifié par son téléphone (comme au passage de commande).
            $client = Client::firstOrCreate(
                ['telephone' => $tel],
                ['nom' => $nom, 'prenom' => $prenom, 'email' => $email],
            );

            $nbCommandes = rand(1, 2);

            for ($c = 0; $c < $nbCommandes; $c++) {
                $selection = $plats->random(rand(1, 3));
                $date = Carbon::now()->subDays(rand(0, 6))->subHours(rand(0, 12));

                $commande = Commande::create([
                    'client_id' => $client->id,
                    'date_commande' => $date,
                    'montant_total' => 0,
                    'adresse_livraison' => $adresses[$i % count($adresses)],
                    'nom_recepteur' => $prenom.' '.$nom,
                    'telephone_recepteur' => $tel,
                    'statut' => $statuts[array_rand($statuts)],
                ]);

                $total = 0;
                foreach ($selection as $plat) {
                    $qte = rand(1, 3);
                    $sousTotal = round($plat->prix * $qte, 2);
                    $total += $sousTotal;

                    $commande->lignes()->create([
                        'plat_id' => $plat->id,
                        'quantite' => $qte,
                        'prix_unitaire' => $plat->prix,
                        'sous_total' => $sousTotal,
                    ]);
                }

                $commande->update(['montant_total' => $total]);
            }
        }
    }
}
