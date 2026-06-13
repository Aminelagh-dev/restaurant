<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Compte gérant (back-office). 'role' n'étant pas mass-assignable,
        // on l'affecte explicitement via forceFill.
        User::updateOrCreate(
            ['email' => 'admin@riad.test'],
            [
                'nom' => 'Gérant',
                'prenom' => 'Riad',
                'telephone' => '0600000000',
                'password' => Hash::make('password'),
            ]
        )->forceFill(['role' => User::ROLE_ADMIN])->save();

        $this->call([
            CarteSeeder::class,
            CommandeSeeder::class,
        ]);
    }
}
