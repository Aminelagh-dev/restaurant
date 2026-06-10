---
name: commande-flow
description: Implémenter le flux panier → commande → suivi livraison. Utiliser pour panier client, formulaire commande ou changement de statut.
paths:
  - "app/Http/Controllers/CommandeController.php"
  - "app/Models/Commande.php"
  - "app/Models/Client.php"
---

# Flux commande client

## Parcours utilisateur

```
Menu → Ajouter au panier → Modifier quantités → Valider commande → Suivi statut
```

## Panier (session)

```php
// Structure session 'panier'
[plat_id => quantite]
```

Méthodes : `ajouter`, `modifierQuantite`, `supprimer`, `vider`, `total()`

## Création commande (`store`)

1. Valider : adresse, nom, prénom, email, panier non vide
2. Vérifier stock de chaque plat
3. Transaction DB :
   - `Client::firstOrCreate(['email' => ...], [...])`
   - `Commande::create(['adresse_livraison' => ..., 'statut' => 'En préparation'])`
   - Attacher plats via `commandes_plats` avec quantité et prix_unitaire
4. Vider session panier
5. Redirect `commandes.suivi` avec ID commande

## Suivi (`show`)

Afficher statut actuel avec indicateur visuel (timeline 3 étapes).

## Back-office — mise à jour statut

`CommandeController@updateStatut` :
- Valider transition autorisée
- Mettre à jour `statut` ou `statut_id`
- Option : notification ou refresh temps réel (hors scope MVP)

## Migration pivot à compléter

Si `commandes_plats` est vide, ajouter :

```php
$table->foreignId('commande_id')->constrained()->cascadeOnDelete();
$table->foreignId('plat_id')->constrained('plats');
$table->unsignedInteger('quantite')->default(1);
$table->decimal('prix_unitaire', 8, 2);
```
