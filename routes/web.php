<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategorieController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\CommandeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PlatsController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\SuiviController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Front-Office — espace client
|--------------------------------------------------------------------------
*/

// Changement de langue (fr / en / ar) — mémorisé en session
Route::get('/locale/{locale}', function (string $locale) {
    if (array_key_exists($locale, config('locales.supported', []))) {
        session(['locale' => $locale]);
    }

    return redirect()->back();
})->name('locale.switch');

// Menu gastronomique + détail d'un plat
Route::get('/', [MenuController::class, 'index'])->name('menu.index');
Route::get('/plats/{plat}', [MenuController::class, 'show'])->name('menu.show');

// Panier (stocké en session)
Route::controller(PanierController::class)->prefix('panier')->name('panier.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/{plat}', 'store')->name('store');
    Route::patch('/{plat}', 'update')->name('update');
    Route::delete('/{plat}', 'destroy')->name('destroy');
    Route::delete('/', 'clear')->name('clear');
});

// Passage de commande
Route::get('/commander', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/commander', [CheckoutController::class, 'store'])->name('checkout.store');

// Suivi de commande
Route::get('/suivi', [SuiviController::class, 'index'])->name('suivi.index');
Route::post('/suivi', [SuiviController::class, 'search'])
    ->middleware('throttle:10,1')
    ->name('suivi.search');
Route::get('/suivi/{commande}', [SuiviController::class, 'show'])->name('suivi.show');

/*
|--------------------------------------------------------------------------
| Back-Office — espace gestionnaire (/admin)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {

    // Authentification gérant (accessible sans être connecté)
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])
            ->middleware('throttle:6,1')
            ->name('login.attempt');
    });

    Route::post('logout', [AuthController::class, 'logout'])
        ->middleware('auth')
        ->name('logout');

    // Espace gérant — réservé aux utilisateurs authentifiés ayant le rôle « admin »
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Gestion de la carte (CRUD plats)
        Route::resource('plats', PlatsController::class)->except(['show']);

        // Gestion des catégories
        Route::resource('categories', CategorieController::class)
            ->parameters(['categories' => 'categorie'])
            ->except(['show']);

        // Gestion des commandes (liste chronologique + changement de statut)
        Route::get('commandes', [CommandeController::class, 'index'])->name('commandes.index');
        Route::get('commandes/{commande}', [CommandeController::class, 'show'])->name('commandes.show');
        Route::patch('commandes/{commande}/statut', [CommandeController::class, 'updateStatut'])->name('commandes.statut');

        // Gestion des clients
        Route::resource('clients', ClientController::class)->except(['show']);
    });
});
