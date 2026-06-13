<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlatRequest;
use App\Http\Requests\UpdatePlatRequest;
use App\Models\Categorie;
use App\Models\Plat;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlatsController extends Controller
{
    /**
     * Liste de la carte avec recherche et filtre par catégorie.
     */
    public function index(Request $request): View
    {
        $plats = Plat::query()
            ->with('categorie')
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where('nom', 'like', '%'.$request->query('q').'%');
            })
            ->when($request->filled('categorie'), function ($query) use ($request) {
                $query->where('categorie_id', $request->query('categorie'));
            })
            ->orderBy('nom')
            ->paginate(12)
            ->withQueryString();

        /** @var LengthAwarePaginator $plats */
        return view('admin.plats.index', [
            'plats' => $plats,
            'categories' => Categorie::orderBy('nom')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.plats.create', [
            'plat' => new Plat(['disponible' => true]),
            'categories' => Categorie::orderBy('nom')->get(),
        ]);
    }

    public function store(StorePlatRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['image'] = $this->resolveImage($request);
        $data['disponible'] = $request->boolean('disponible');

        Plat::create($data);

        return redirect()->route('admin.plats.index')
            ->with('success', __('Plat ajouté à la carte.'));
    }

    public function edit(Plat $plat): View
    {
        return view('admin.plats.edit', [
            'plat' => $plat,
            'categories' => Categorie::orderBy('nom')->get(),
        ]);
    }

    public function update(UpdatePlatRequest $request, Plat $plat): RedirectResponse
    {
        $data = $request->validated();
        $data['image'] = $this->resolveImage($request, $plat->image);
        $data['disponible'] = $request->boolean('disponible');

        $plat->update($data);

        return redirect()->route('admin.plats.index')
            ->with('success', __('Plat mis à jour.'));
    }

    public function destroy(Plat $plat): RedirectResponse
    {
        // Préserve l'historique : un plat déjà commandé ne peut être supprimé
        // (sinon ses lignes de commande seraient effacées). On invite à le
        // marquer « Épuisé » à la place.
        if ($plat->commandes()->exists()) {
            return back()->with('error', __('« :nom » figure dans des commandes : marquez-le « Épuisé » plutôt que de le supprimer.', ['nom' => $plat->nom]));
        }

        $plat->delete();

        return redirect()->route('admin.plats.index')
            ->with('success', __('Plat supprimé.'));
    }

    /**
     * Extensions autorisées dérivées du type MIME réel (jamais du nom de fichier client).
     */
    private const MIME_EXTENSIONS = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];

    /**
     * Résout l'image : fichier uploadé prioritaire, sinon URL saisie,
     * sinon valeur courante conservée. L'extension est déduite du type MIME
     * vérifié côté serveur, jamais du nom fourni par le client.
     */
    private function resolveImage(Request $request, ?string $current = null): ?string
    {
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $extension = self::MIME_EXTENSIONS[$file->getMimeType()] ?? null;

            // Type non autorisé : on ignore le fichier et on conserve l'existant.
            if ($extension === null) {
                return $current;
            }

            $name = uniqid('plat_').'.'.$extension;
            $file->move(public_path('uploads/plats'), $name);

            return 'uploads/plats/'.$name;
        }

        $url = trim((string) $request->input('image', ''));

        return $url !== '' ? $url : $current;
    }
}
