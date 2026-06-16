<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Réserve l'accès à l'espace gérant (/admin) aux comptes du back-office :
 * rôle « admin » ou « operator », ET compte actif. Doit être appliqué après le
 * middleware « auth ». La restriction de l'opérateur à la seule page des
 * commandes est portée par le routage (middleware « admin » sur le reste).
 */
class EnsureCanAccessBackOffice
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Compte back-office actif : accès autorisé.
        if ($user && $user->peutAccederBackOffice()) {
            return $next($request);
        }

        // Compte désactivé en cours de session : déconnexion propre.
        if ($user && ($user->isAdmin() || $user->isOperator()) && ! $user->actif) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')
                ->with('error', __('Votre compte a été désactivé.'));
        }

        abort(403, __('Accès réservé au gérant.'));
    }
}
