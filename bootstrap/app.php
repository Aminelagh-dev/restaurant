<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Applique la langue choisie (fr/en/ar) à chaque requête web.
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        // Alias des contrôles d'accès au back-office.
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'staff' => \App\Http\Middleware\EnsureCanAccessBackOffice::class,
        ]);

        // Redirections d'authentification vers l'espace gérant. Un opérateur
        // est dirigé vers les commandes (seule page qui lui est accessible).
        $middleware->redirectGuestsTo(fn () => route('admin.login'));
        $middleware->redirectUsersTo(fn ($request) => route(
            $request->user()?->routeAccueilBackOffice() ?? 'admin.dashboard'
        ));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
