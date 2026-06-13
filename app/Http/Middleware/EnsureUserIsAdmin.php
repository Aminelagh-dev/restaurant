<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Réserve l'accès au back-office (/admin) aux comptes ayant le rôle « admin ».
 * Doit être appliqué après le middleware « auth » (l'utilisateur est authentifié).
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        abort_unless($user && $user->isAdmin(), 403, __('Accès réservé au gérant.'));

        return $next($request);
    }
}
