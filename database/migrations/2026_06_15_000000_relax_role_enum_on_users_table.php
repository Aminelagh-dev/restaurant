<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * La colonne `role` était un enum limité à (client, admin). On la convertit
     * en chaîne libre pour accueillir le rôle « operator » (et rester souple
     * pour de futurs rôles) ; les valeurs autorisées sont validées côté appli.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('client')->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['client', 'admin'])->default('client')->change();
        });
    }
};
