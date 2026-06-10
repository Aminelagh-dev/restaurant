---
name: scaffold-routes
description: Générer la structure des routes web front-office et admin
---

# Scaffold Routes

Propose et implémente la structure des routes dans `routes/web.php`.

## Structure cible

```php
// Front-office
Route::get('/', ...)->name('home');
Route::get('/menu', ...)->name('menu.index');
Route::get('/menu/{plat}', ...)->name('menu.show');
Route::prefix('panier')->name('panier.')->group(...);
Route::prefix('commandes')->name('commandes.')->group(...);

// Back-office
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::resource('plats', PlatsController::class);
    Route::resource('categories', CategorieController::class);
    Route::resource('thematiques', ThematiqueController::class);
    Route::resource('commandes', CommandeController::class)->only(['index', 'show', 'update']);
    Route::get('dashboard', ...)->name('dashboard');
});
```

## Étapes

1. Si scope large → `/plan-feature` d'abord
2. Ajouter routes avec noms explicites
3. Créer méthodes controller stub si manquantes
4. `php artisan route:list` pour vérifier

Ne pas implémenter la logique métier complète — focus structure routage.
