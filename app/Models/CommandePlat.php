<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommandePlat extends Model
{
    protected $table = 'commande_plat';

    protected $fillable = [
        'commande_id',
        'plat_id',
        'quantite',
        'prix_unitaire',
        'sous_total',
    ];

    protected $casts = [
        'quantite' => 'integer',
        'prix_unitaire' => 'decimal:2',
        'sous_total' => 'decimal:2',
    ];

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    public function plat(): BelongsTo
    {
        return $this->belongsTo(Plats::class, 'plat_id');
    }
}
