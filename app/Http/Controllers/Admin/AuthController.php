<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Authentification du back-office (espace gérant).
 */
class AuthController extends Controller
{
    /**
     * Formulaire de connexion gérant.
     */
    public function showLogin(): View
    {
        return view('admin.auth.login');
    }

    /**
     * Vérifie les identifiants et ouvre la session gérant.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [], [
            'email' => __('adresse e-mail'),
            'password' => __('mot de passe'),
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('Identifiants incorrects.'),
            ]);
        }

        // Défense en profondeur : seuls les rôles « admin » et « operator »
        // accèdent au back-office. On referme la session d'authentification,
        // puis on renvoie l'erreur au formulaire (sans invalider la session
        // pour préserver le message flashé).
        if (! $request->user()->isAdmin() && ! $request->user()->isOperator()) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => __("Ce compte n'a pas accès à l'espace gérant."),
            ]);
        }

        // Un compte désactivé ne peut pas se connecter.
        if (! $request->user()->actif) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => __('Ce compte a été désactivé. Contactez un administrateur.'),
            ]);
        }

        $request->session()->regenerate();

        // Chaque rôle est dirigé vers sa page d'accueil (l'opérateur vers les
        // commandes), sauf si une page précise était demandée avant connexion.
        return redirect()->intended(route($request->user()->routeAccueilBackOffice()));
    }

    /**
     * Ferme la session gérant.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('menu.index');
    }
}
