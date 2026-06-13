<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applique la langue choisie par le visiteur (stockée en session) à chaque
 * requête. À défaut, retombe sur la langue par défaut configurée (français).
 */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supported = array_keys(config('locales.supported', []));
        $default = config('locales.default', 'fr');

        $locale = $request->session()->get('locale', $default);

        if (! in_array($locale, $supported, true)) {
            $locale = $default;
        }

        app()->setLocale($locale);
        // Synchronise Carbon pour que les dates traduites (translatedFormat) suivent la langue.
        Carbon::setLocale($locale);

        return $next($request);
    }
}
