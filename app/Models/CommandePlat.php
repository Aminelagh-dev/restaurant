<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommandePlat extends Model
{
    protected $table = 'commande_plat';

    protected $fillable = [
        'commande_id',
        'plat_id',
        'quantite',
        'prix_unitaire',
        'sous_total'
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function plat()
    {
        return $this->belongsTo(Plat::class);
    }
}
