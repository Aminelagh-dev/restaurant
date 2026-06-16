<?php

namespace Tests\Feature;

use App\Models\Categorie;
use App\Models\Plat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PanierAjaxTest extends TestCase
{
    use RefreshDatabase;

    private function plat(bool $disponible = true): Plat
    {
        $categorie = Categorie::create(['nom' => 'Plats', 'description' => null]);

        return Plat::create([
            'categorie_id' => $categorie->id,
            'nom' => 'Tajine',
            'description' => 'Bon',
            'ingredients' => 'x',
            'temps_preparation' => 30,
            'prix' => 80,
            'disponible' => $disponible,
        ]);
    }

    public function test_ajax_add_returns_json_with_updated_count(): void
    {
        $plat = $this->plat();

        $response = $this->postJson(route('panier.store', $plat), ['quantite' => 2]);

        $response->assertOk()
            ->assertJson(['ok' => true, 'count' => 2]);
        $this->assertNotEmpty($response->json('message'));
    }

    public function test_ajax_add_out_of_stock_returns_422(): void
    {
        $plat = $this->plat(disponible: false);

        $this->postJson(route('panier.store', $plat), ['quantite' => 1])
            ->assertStatus(422)
            ->assertJson(['ok' => false, 'count' => 0]);
    }

    public function test_non_ajax_add_still_redirects_back(): void
    {
        $plat = $this->plat();

        $this->from(route('menu.index'))
            ->post(route('panier.store', $plat), ['quantite' => 1])
            ->assertRedirect(route('menu.index'))
            ->assertSessionHas('success');
    }

    public function test_ajax_update_returns_recomputed_totals(): void
    {
        $plat = $this->plat(); // prix 80
        $this->postJson(route('panier.store', $plat), ['quantite' => 1]);

        $response = $this->patchJson(route('panier.update', $plat), ['quantite' => 3]);

        $response->assertOk()->assertJson(['ok' => true, 'count' => 3, 'quantite' => 3]);
        // 3 × 80 = 240,00 DH (séparateur décimal « , »).
        $this->assertStringContainsString('240,00', $response->json('sousTotal'));
        $this->assertStringContainsString('240,00', $response->json('total'));
    }

    public function test_non_ajax_update_still_redirects_back(): void
    {
        $plat = $this->plat();
        $this->post(route('panier.store', $plat), ['quantite' => 1]);

        $this->from(route('panier.index'))
            ->patch(route('panier.update', $plat), ['quantite' => 2])
            ->assertRedirect(route('panier.index'))
            ->assertSessionHas('success');
    }
}
