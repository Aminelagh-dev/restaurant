---
name: seed-demo-data
description: Créer des seeders avec plats marocains, catégories et statuts de démo
---

# Seed Données Démo

Crée ou complète les seeders pour une démo réaliste de cuisine marocaine.

## Données à inclure

### Catégories
Entrées, Plats principaux, Desserts, Thés

### Thématiques
Spécialités de Fès, Marrakech, Casablanca, Rabat

### Statuts commande
En préparation, En cours de livraison, Livrée

### Plats (exemples)
| Plat | Catégorie | Région |
|------|-----------|--------|
| Tagine poulet citron confit | Plats principaux | Marrakech |
| Couscous royal | Plats principaux | Fès |
| Pastilla au pigeon | Entrées | Fès |
| Rfissa | Plats principaux | Casablanca |
| Harira | Entrées | Rabat |
| Thé à la menthe | Thés | — |
| Chebakia | Desserts | Fès |

## Fichiers

- `database/seeders/DatabaseSeeder.php` — orchestration
- Seeders dédiés si volume important : `PlatSeeder`, `CategorieSeeder`, etc.

## Exécution

```bash
php artisan migrate:fresh --seed
```

Prix réalistes en MAD (ex. 45.00 – 120.00). Images : placeholder ou chemins cohérents.
