<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Commande;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminStatutSelectionTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $user = new User(['nom' => 'Ad', 'prenom' => 'T', 'email' => 'ad@test.dev', 'password' => 'password']);
        $user->forceFill(['role' => User::ROLE_ADMIN, 'actif' => true])->save();

        return $user;
    }

    private function commande(string $statut): Commande
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
        $commande->forceFill(['statut' => $statut])->save();

        return $commande;
    }

    public function test_selectable_statuses_are_past_plus_one_next(): void
    {
        // En attente (1re étape) : étape courante + la suivante uniquement.
        $this->assertSame(
            [Commande::STATUT_ATTENTE, Commande::STATUT_PREPARATION],
            array_keys($this->commande(Commande::STATUT_ATTENTE)->statutsSelectionnables()),
        );

        // En livraison : toutes les étapes passées + la livraison + livrée.
        $this->assertSame(
            [Commande::STATUT_ATTENTE, Commande::STATUT_PREPARATION, Commande::STATUT_LIVRAISON, Commande::STATUT_LIVREE],
            array_keys($this->commande(Commande::STATUT_LIVRAISON)->statutsSelectionnables()),
        );

        // Livrée (dernière étape) : pas de suivant, on borne aux 4 statuts.
        $this->assertSame(
            array_keys(Commande::STATUTS),
            array_keys($this->commande(Commande::STATUT_LIVREE)->statutsSelectionnables()),
        );
    }

    public function test_admin_can_advance_one_step_and_roll_back(): void
    {
        $admin = $this->admin();
        $commande = $this->commande(Commande::STATUT_PREPARATION);

        // Avancer d'un cran : autorisé.
        $this->actingAs($admin)
            ->patch(route('admin.commandes.statut', $commande), ['statut' => Commande::STATUT_LIVRAISON])
            ->assertRedirect();
        $this->assertSame(Commande::STATUT_LIVRAISON, $commande->fresh()->statut);

        // Revenir à une étape antérieure : autorisé.
        $this->actingAs($admin)
            ->patch(route('admin.commandes.statut', $commande), ['statut' => Commande::STATUT_ATTENTE])
            ->assertRedirect();
        $this->assertSame(Commande::STATUT_ATTENTE, $commande->fresh()->statut);
    }

    public function test_admin_cannot_skip_more_than_one_step_forward(): void
    {
        $admin = $this->admin();
        $commande = $this->commande(Commande::STATUT_ATTENTE);

        // Sauter deux crans (attente -> livraison) : interdit.
        $this->actingAs($admin)
            ->patch(route('admin.commandes.statut', $commande), ['statut' => Commande::STATUT_LIVRAISON])
            ->assertForbidden();
        $this->assertSame(Commande::STATUT_ATTENTE, $commande->fresh()->statut);
    }
}
