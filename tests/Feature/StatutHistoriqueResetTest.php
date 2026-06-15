<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Commande;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatutHistoriqueResetTest extends TestCase
{
    use RefreshDatabase;

    private function commandeLivree(): Commande
    {
        $client = Client::create(['nom' => 'Doe', 'prenom' => 'Jane', 'telephone' => '0600000000']);

        $commande = new Commande([
            'client_id' => $client->id,
            'date_commande' => now(),
            'montant_total' => 100,
            'adresse_livraison' => 'Rue X',
            'nom_recepteur' => 'Jane',
            'telephone_recepteur' => '0600000000',
        ]);
        $commande->forceFill(['statut' => Commande::STATUT_ATTENTE])->save();

        // Progression normale : crée une entrée d'historique par étape (sauf attente).
        $commande->changerStatut(Commande::STATUT_PREPARATION);
        $commande->changerStatut(Commande::STATUT_LIVRAISON);
        $commande->changerStatut(Commande::STATUT_LIVREE);

        return $commande;
    }

    public function test_progression_normale_journalise_chaque_etape(): void
    {
        $commande = $this->commandeLivree();

        $this->assertEqualsCanonicalizing(
            [Commande::STATUT_PREPARATION, Commande::STATUT_LIVRAISON, Commande::STATUT_LIVREE],
            $commande->historiqueStatuts()->pluck('statut')->all(),
        );
    }

    public function test_retour_en_arriere_supprime_les_statuts_posterieurs(): void
    {
        $commande = $this->commandeLivree();

        // Le gérant remet la commande en préparation.
        $commande->changerStatut(Commande::STATUT_PREPARATION);

        // Seule l'étape « en préparation » subsiste, et une seule fois (réécrite).
        $this->assertSame(
            [Commande::STATUT_PREPARATION],
            $commande->historiqueStatuts()->pluck('statut')->all(),
        );
        $this->assertSame(Commande::STATUT_PREPARATION, $commande->fresh()->statut);
    }

    public function test_retour_a_en_attente_vide_l_historique(): void
    {
        $commande = $this->commandeLivree();

        $commande->changerStatut(Commande::STATUT_ATTENTE);

        $this->assertSame(0, $commande->historiqueStatuts()->count());
        $this->assertSame(Commande::STATUT_ATTENTE, $commande->fresh()->statut);
    }

    public function test_avancer_ne_supprime_rien(): void
    {
        $client = Client::create(['nom' => 'Doe', 'prenom' => 'Jane', 'telephone' => '0600000000']);
        $commande = new Commande([
            'client_id' => $client->id,
            'date_commande' => now(),
            'montant_total' => 50,
            'adresse_livraison' => 'Rue Y',
            'nom_recepteur' => 'Jane',
            'telephone_recepteur' => '0600000000',
        ]);
        $commande->forceFill(['statut' => Commande::STATUT_ATTENTE])->save();

        $commande->changerStatut(Commande::STATUT_PREPARATION);
        $commande->changerStatut(Commande::STATUT_LIVRAISON);

        $this->assertSame(
            [Commande::STATUT_PREPARATION, Commande::STATUT_LIVRAISON],
            $commande->historiqueStatuts()->pluck('statut')->all(),
        );
    }
}
