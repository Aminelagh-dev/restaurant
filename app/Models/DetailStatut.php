<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailStatut extends Model
{
    protected $table = 'details_statuses';

    protected $fillable = [
        'commande_id',
        'statut',
        'date_action',
    ];

    protected $casts = [
        'date_action' => 'datetime',
    ];

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    /**
     * Libellé affiché du statut historisé.
     */
    public function statutLabel(): string
    {
        return Commande::STATUTS[$this->statut] ?? $this->statut;
    }
}
