<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Historique des changements de statut d'une commande : une ligne par
     * transition, avec la date/heure de l'action.
     */
    public function up(): void
    {
        Schema::create('details_statuses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('commande_id')
                ->constrained('commandes')
                ->cascadeOnDelete();

            $table->enum('statut', [
                'en_attente',
                'en_preparation',
                'en_livraison',
                'livree',
            ]);

            $table->dateTime('date_action');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('details_statuses');
    }
};
