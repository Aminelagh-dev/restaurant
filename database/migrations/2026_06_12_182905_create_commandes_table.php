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
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('client_id')
                ->constrained('clients')
                ->cascadeOnDelete();

            $table->dateTime('date_commande');

            $table->decimal('montant_total', 10, 2);

            $table->string('adresse_livraison');

            $table->string('nom_recepteur');

            $table->string('telephone_recepteur');

            $table->enum('statut', [
                'en_preparation',
                'en_livraison',
                'livree'
            ])->default('en_preparation');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
