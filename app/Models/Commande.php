<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $fillable = [
        'client_id',
        'date_commande',
        'montant_total',
        'adresse_livraison',
        'nom_recepteur',
        'telephone_recepteur',
        'statut'
    ];

    protected $casts = [
        'date_commande' => 'datetime',
        'montant_total' => 'decimal:2'
    ];

    public function clients(){
        return $this->belongsTo(Client::class);
    }
}
