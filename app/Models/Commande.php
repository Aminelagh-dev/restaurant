<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commande extends Model
{
    public const STATUT_ATTENTE = 'en_attente';
    public const STATUT_PREPARATION = 'en_preparation';
    public const STATUT_LIVRAISON = 'en_livraison';
    public const STATUT_LIVREE = 'livree';

    /**
     * Statuts ordonnés et leurs libellés affichés au client.
     */
    public const STATUTS = [
        self::STATUT_ATTENTE => 'En attente',
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
        return $this->belongsToMany(Plat::class, 'commande_plat', 'commande_id', 'plat_id')
            ->withPivot(['quantite', 'prix_unitaire', 'sous_total'])
            ->withTimestamps();
    }

    /**
     * Historique des changements de statut, du plus ancien au plus récent.
     */
    public function historiqueStatuts(): HasMany
    {
        return $this->hasMany(DetailStatut::class)->orderBy('date_action');
    }

    public function statutLabel(): string
    {
        return self::STATUTS[$this->statut] ?? $this->statut;
    }

    /**
     * Change le statut de la commande et journalise la transition dans
     * l'historique (table `details_statuses`).
     */
    public function changerStatut(string $statut, ?Carbon $date = null): void
    {
        $this->statut = $statut;
        $this->save();
        $this->enregistrerHistoriqueStatut($date);
    }

    /**
     * Ajoute une entrée d'historique pour le statut courant de la commande.
     *
     * Le statut initial « en attente » n'est jamais historisé : son horodatage
     * est porté par le champ `created_at` de la commande (pas de duplication).
     */
    public function enregistrerHistoriqueStatut(?Carbon $date = null): ?DetailStatut
    {
        if ($this->statut === self::STATUT_ATTENTE) {
            return null;
        }

        return $this->historiqueStatuts()->create([
            'statut' => $this->statut,
            'date_action' => $date ?? now(),
        ]);
    }
}
