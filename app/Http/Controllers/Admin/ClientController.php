<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $clients = Client::query()
            ->withCount('commandes')
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = $request->query('q');
                $query->where(function ($sub) use ($q) {
                    $sub->where('nom', 'like', "%{$q}%")
                        ->orWhere('prenom', 'like', "%{$q}%")
                        ->orWhere('telephone', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->orderBy('nom')
            ->paginate(15)
            ->withQueryString();

        return view('admin.clients.index', compact('clients'));
    }

    public function create(): View
    {
        return view('admin.clients.create', [
            'client' => new Client(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Client::create($this->validateData($request));

        return redirect()->route('admin.clients.index')
            ->with('success', __('Client créé.'));
    }

    public function edit(Client $client): View
    {
        return view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client): RedirectResponse
    {
        $client->update($this->validateData($request, $client));

        return redirect()->route('admin.clients.index')
            ->with('success', __('Client mis à jour.'));
    }

    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();

        return redirect()->route('admin.clients.index')
            ->with('success', __('Client supprimé.'));
    }

    /**
     * @return array<string,mixed>
     */
    private function validateData(Request $request, ?Client $client = null): array
    {
        return $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:30'],
            'email' => [
                'nullable', 'email', 'max:255',
                Rule::unique('clients', 'email')->ignore($client?->id),
            ],
        ], [], [
            'nom' => __('nom'),
            'prenom' => __('prénom'),
            'telephone' => __('téléphone'),
            'email' => __('email'),
        ]);
    }
}
