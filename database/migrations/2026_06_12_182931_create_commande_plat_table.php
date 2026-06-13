<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('commande_plat', function (Blueprint $table) {
            // Clé primaire de substitution : commande_plat est une vraie table de
            // lignes (quantité + prix figés), pas un simple pivot.
            $table->id();

            $table->foreignId('commande_id')
                ->constrained('commandes')
                ->cascadeOnDelete();

            $table->foreignId('plat_id')
                ->constrained('plats')
                ->cascadeOnDelete();

            $table->integer('quantite');

            $table->decimal('prix_unitaire', 10, 2);

            $table->decimal('sous_total', 10, 2);

            $table->timestamps();

            // Un plat n'apparaît qu'une fois par commande (les quantités sont cumulées).
            $table->unique(['commande_id', 'plat_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commande_plat');
    }
};
