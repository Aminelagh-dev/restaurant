<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Commande;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OperatorAccessTest extends TestCase
{
    use RefreshDatabase;

    private function operator(): User
    {
        $user = new User([
            'nom' => 'Op', 'prenom' => 'Test', 'email' => 'op@test.dev', 'password' => 'password',
        ]);
        $user->forceFill(['role' => User::ROLE_OPERATOR, 'actif' => true])->save();

        return $user;
    }

    private function admin(): User
    {
        $user = new User([
            'nom' => 'Ad', 'prenom' => 'Test', 'email' => 'ad@test.dev', 'password' => 'password',
        ]);
        $user->forceFill(['role' => User::ROLE_ADMIN, 'actif' => true])->save();

        return $user;
    }

    private function commande(string $statut = Commande::STATUT_ATTENTE): Commande
    {
        $client = Client::create([
            'nom' => 'Doe', 'prenom' => 'Jane', 'telephone' => '0600000000',
        ]);

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

    public function test_operator_can_see_orders_but_not_admin_pages(): void
    {
        $op = $this->operator();

        $this->actingAs($op)->get(route('admin.commandes.index'))->assertOk();
        $this->actingAs($op)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($op)->get(route('admin.plats.index'))->assertForbidden();
        $this->actingAs($op)->get(route('admin.equipe.index'))->assertForbidden();
    }

    public function test_operator_can_advance_to_next_status_only(): void
    {
        $op = $this->operator();
        $commande = $this->commande(Commande::STATUT_ATTENTE);

        // Statut suivant autorisé.
        $this->actingAs($op)
            ->patch(route('admin.commandes.statut', $commande), ['statut' => Commande::STATUT_PREPARATION])
            ->assertRedirect();
        $this->assertSame(Commande::STATUT_PREPARATION, $commande->fresh()->statut);

        // Saut de statut interdit (en_preparation -> livree).
        $this->actingAs($op)
            ->patch(route('admin.commandes.statut', $commande), ['statut' => Commande::STATUT_LIVREE])
            ->assertForbidden();
        $this->assertSame(Commande::STATUT_PREPARATION, $commande->fresh()->statut);
    }

    public function test_admin_can_advance_a_status_and_access_dashboard(): void
    {
        $admin = $this->admin();
        $commande = $this->commande(Commande::STATUT_ATTENTE);

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();

        // Le gérant avance d'un cran (attente -> préparation).
        $this->actingAs($admin)
            ->patch(route('admin.commandes.statut', $commande), ['statut' => Commande::STATUT_PREPARATION])
            ->assertRedirect();
        $this->assertSame(Commande::STATUT_PREPARATION, $commande->fresh()->statut);
    }

    public function test_client_role_cannot_reach_back_office(): void
    {
        $client = new User(['nom' => 'C', 'prenom' => 'C', 'email' => 'c@test.dev', 'password' => 'password']);
        $client->forceFill(['role' => User::ROLE_CLIENT, 'actif' => true])->save();

        $this->actingAs($client)->get(route('admin.commandes.index'))->assertForbidden();
    }
}
