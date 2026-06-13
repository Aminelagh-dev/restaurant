<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Réserve l'accès au back-office (/admin) aux gérants : rôle « admin » ET compte
 * actif. Doit être appliqué après le middleware « auth » (utilisateur authentifié).
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Gérant actif : accès autorisé.
        if ($user && $user->peutGerer()) {
            return $next($request);
        }

        // Gérant désactivé en cours de session : on le déconnecte proprement.
        if ($user && $user->isAdmin() && ! $user->actif) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')
                ->with('error', __('Votre compte a été désactivé.'));
        }

        abort(403, __('Accès réservé au gérant.'));
    }
}
