<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Plats extends Model
{
    protected $table = 'plats';

    protected $fillable = [
        'categorie_id',
        'nom',
        'description',
        'ingredients',
        'temps_preparation',
        'prix',
        'image',
        'stock',
        'disponible',
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'temps_preparation' => 'integer',
        'stock' => 'integer',
        'disponible' => 'boolean',
    ];

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }

    public function commandes(): BelongsToMany
    {
        return $this->belongsToMany(Commande::class, 'commande_plat', 'plat_id', 'commande_id')
            ->withPivot(['quantite', 'prix_unitaire', 'sous_total'])
            ->withTimestamps();
    }

    /**
     * Un plat est considéré en rupture si marqué indisponible ou stock épuisé.
     */
    public function estEpuise(): bool
    {
        return ! $this->disponible || $this->stock <= 0;
    }

    /**
     * URL exploitable de l'image : URL absolue conservée telle quelle,
     * chemin local résolu via asset(). Vide si aucune image.
     */
    public function getImageUrlAttribute(): string
    {
        if (! $this->image) {
            return '';
        }

        return str_starts_with($this->image, 'http')
            ? $this->image
            : asset($this->image);
    }
}
