<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Gestion des comptes gérants (création, modification, activation/désactivation).
 */
class StaffController extends Controller
{
    public function index(): View
    {
        $gerants = User::query()
            ->where('role', User::ROLE_ADMIN)
            ->orderByDesc('actif')
            ->orderBy('nom')
            ->get();

        return view('admin.equipe.index', [
            'gerants' => $gerants,
            'actifs' => $gerants->where('actif', true)->count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.equipe.create', [
            'gerant' => new User(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        $user = new User([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'telephone' => $data['telephone'] ?? null,
            'password' => $data['password'],
        ]);
        // 'role' et 'actif' ne sont pas mass-assignables : affectation explicite.
        $user->role = User::ROLE_ADMIN;
        $user->actif = true;
        $user->save();

        return redirect()->route('admin.equipe.index')
            ->with('success', __('Gérant créé.'));
    }

    public function edit(User $user): View
    {
        return view('admin.equipe.edit', ['gerant' => $user]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validateData($request, $user);

        $user->fill([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'telephone' => $data['telephone'] ?? null,
        ]);

        // Mot de passe seulement s'il est renseigné (laisser vide = inchangé).
        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();

        return redirect()->route('admin.equipe.index')
            ->with('success', __('Gérant mis à jour.'));
    }

    /**
     * Active ou désactive un compte gérant.
     */
    public function toggleStatut(Request $request, User $user): RedirectResponse
    {
        // Garde-fou : on ne peut pas désactiver son propre compte.
        if ($user->is($request->user())) {
            return back()->with('error', __('Vous ne pouvez pas désactiver votre propre compte.'));
        }

        // Garde-fou : ne pas désactiver le dernier gérant actif.
        if ($user->actif) {
            $resteUnActif = User::query()
                ->where('role', User::ROLE_ADMIN)
                ->where('actif', true)
                ->whereKeyNot($user->id)
                ->exists();

            if (! $resteUnActif) {
                return back()->with('error', __('Impossible de désactiver le dernier gérant actif.'));
            }
        }

        $user->actif = ! $user->actif;
        $user->save();

        return back()->with('success', $user->actif
            ? __('Gérant réactivé.')
            : __('Gérant désactivé.'));
    }

    /**
     * @return array<string,mixed>
     */
    private function validateData(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'telephone' => ['nullable', 'string', 'max:30'],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
        ], [], [
            'nom' => __('nom'),
            'prenom' => __('prénom'),
            'email' => __('email'),
            'telephone' => __('téléphone'),
            'password' => __('mot de passe'),
        ]);
    }
}
