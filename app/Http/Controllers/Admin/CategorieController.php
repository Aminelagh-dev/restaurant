<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategorieController extends Controller
{
    public function index(): View
    {
        $categories = Categorie::query()
            ->withCount('plats')
            ->orderBy('nom')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create', [
            'categorie' => new Categorie(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        Categorie::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie créée.');
    }

    public function edit(Categorie $categorie): View
    {
        return view('admin.categories.edit', compact('categorie'));
    }

    public function update(Request $request, Categorie $categorie): RedirectResponse
    {
        $categorie->update($this->validateData($request));

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(Categorie $categorie): RedirectResponse
    {
        if ($categorie->plats()->exists()) {
            return back()->with('error', 'Impossible de supprimer une catégorie contenant des plats.');
        }

        $categorie->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée.');
    }

    /**
     * @return array<string,mixed>
     */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ], [], [
            'nom' => 'nom',
            'description' => 'description',
        ]);
    }
}
