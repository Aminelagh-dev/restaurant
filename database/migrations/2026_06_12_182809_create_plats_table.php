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
        Schema::create('plats', function (Blueprint $table) {
            $table->id();

            $table->foreignId('categorie_id')
                ->constrained('categories')
                ->cascadeOnDelete();

            $table->string('nom');
            $table->text('description');
            $table->text('ingredients');
            $table->integer('temps_preparation');
            $table->decimal('prix', 10, 2);

            $table->string('image')->nullable();

            $table->integer('stock')->default(0);

            $table->boolean('disponible')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plats');
    }
};
