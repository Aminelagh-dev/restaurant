<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commande extends Model
{
    public const STATUT_PREPARATION = 'en_preparation';
    public const STATUT_LIVRAISON = 'en_livraison';
    public const STATUT_LIVREE = 'livree';

    /**
     * Statuts ordonnés et leurs libellés affichés au client.
     */
    public const STATUTS = [
        self::STATUT_PREPARATION => 'En préparation',
        self::STATUT_LIVRAISON => 'En cours de livraison',
        self::STATUT_LIVREE => 'Livrée',
    ];

    // 'statut' est volontairement exclu de $fillable (colonne d'état privilégiée) :
    // il est affecté explicitement à la création et lors des changements de statut.
    protected $fillable = [
        'client_id',
        'date_commande',
        'montant_total',
        'adresse_livraison',
        'nom_recepteur',
        'telephone_recepteur',
    ];

    protected $casts = [
        'date_commande' => 'datetime',
        'montant_total' => 'decimal:2',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(CommandePlat::class);
    }

    public function plats(): BelongsToMany
    {
        return $this->belongsToMany(Plats::class, 'commande_plat', 'commande_id', 'plat_id')
            ->withPivot(['quantite', 'prix_unitaire', 'sous_total'])
            ->withTimestamps();
    }

    public function statutLabel(): string
    {
        return self::STATUTS[$this->statut] ?? $this->statut;
    }
}
