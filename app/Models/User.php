<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_CLIENT = 'client';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_OPERATOR = 'operator';

    /**
     * Rôles disposant d'un accès au back-office (espace gérant) et leurs libellés.
     */
    public const ROLES_BACK_OFFICE = [
        self::ROLE_ADMIN => 'Gérant',
        self::ROLE_OPERATOR => 'Opérateur',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    // 'role' est volontairement exclu de $fillable (colonne privilégiée) pour
    // éviter toute escalade via mass-assignment ; il est affecté explicitement.
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'actif' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isOperator(): bool
    {
        return $this->role === self::ROLE_OPERATOR;
    }

    /**
     * Le gérant peut-il accéder au back-office : rôle admin ET compte actif.
     */
    public function peutGerer(): bool
    {
        return $this->isAdmin() && (bool) $this->actif;
    }

    /**
     * L'utilisateur peut-il accéder à l'espace gérant : rôle admin OU opérateur,
     * ET compte actif. L'opérateur n'a accès qu'à la page des commandes ;
     * la restriction fine est portée par le routage et les vues.
     */
    public function peutAccederBackOffice(): bool
    {
        return ($this->isAdmin() || $this->isOperator()) && (bool) $this->actif;
    }

    /**
     * Libellé lisible du rôle (« Gérant », « Opérateur »…).
     */
    public function roleLabel(): string
    {
        return self::ROLES_BACK_OFFICE[$this->role] ?? $this->role;
    }

    /**
     * Nom de la route d'accueil du back-office selon le rôle : l'opérateur est
     * dirigé vers les commandes (seule page qui lui est accessible).
     */
    public function routeAccueilBackOffice(): string
    {
        return $this->isOperator() ? 'admin.commandes.index' : 'admin.dashboard';
    }
}
