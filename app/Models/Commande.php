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
     * Statut immédiatement suivant dans le cycle de vie de la commande, ou
     * null si la commande est déjà livrée (dernier statut).
     */
    public function statutSuivant(): ?string
    {
        $cles = array_keys(self::STATUTS);
        $position = array_search($this->statut, $cles, true);

        if ($position === false || $position + 1 >= count($cles)) {
            return null;
        }

        return $cles[$position + 1];
    }

    public function statutSuivantLabel(): ?string
    {
        $suivant = $this->statutSuivant();

        return $suivant !== null ? self::STATUTS[$suivant] : null;
    }

    /**
     * Position (index) d'un statut dans le cycle de vie ordonné, ou -1 s'il
     * est inconnu. Par défaut, position du statut courant de la commande.
     */
    public function positionStatut(?string $statut = null): int
    {
        $position = array_search($statut ?? $this->statut, array_keys(self::STATUTS), true);

        return $position === false ? -1 : $position;
    }

    /**
     * Statuts proposés au gérant : tous les statuts déjà atteints (statut
     * courant inclus) ainsi que le tout premier statut suivant — afin de
     * pouvoir revenir à une étape antérieure ou n'avancer que d'un cran.
     *
     * @return array<string,string>  clé du statut => libellé
     */
    public function statutsSelectionnables(): array
    {
        // Longueur = positions 0..(courant + 1) ; array_slice borne au nombre réel.
        return array_slice(self::STATUTS, 0, max($this->positionStatut(), 0) + 2, true);
    }

    /**
     * Change le statut de la commande et journalise la transition dans
     * l'historique (table `details_statuses`).
     *
     * Lors d'un retour en arrière (le gérant remet un statut antérieur), on
     * purge de l'historique les entrées du statut cible et de tous les statuts
     * postérieurs, puis on ré-enregistre proprement le statut cible : la
     * commande réapparaît « comme neuve » à cette étape, sans trace des étapes
     * suivantes (le suivi client les réaffiche alors comme à venir).
     */
    public function changerStatut(string $statut, ?Carbon $date = null): void
    {
        $enArriere = $this->positionStatut($statut) < $this->positionStatut();

        $this->statut = $statut;
        $this->save();

        if ($enArriere) {
            $cles = array_keys(self::STATUTS);
            $statutsAPurger = array_slice($cles, max($this->positionStatut($statut), 0));

            $this->historiqueStatuts()->whereIn('statut', $statutsAPurger)->delete();
        }

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
