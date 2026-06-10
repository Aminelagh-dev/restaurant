---
name: add-plat-crud
description: Créer ou compléter le CRUD admin des plats marocains (carte, prix, stock, image). Utiliser pour gestion carte back-office ou ajout de plats traditionnels.
paths:
  - "app/Http/Controllers/PlatsController.php"
  - "app/Models/Plats.php"
  - "resources/views/**/plats/**"
---

# CRUD Plats — Back-office

## Objectif

Permettre au gérant d'ajouter, modifier, supprimer des plats et de marquer un plat comme épuisé.

## Champs plat

| Champ | Source | Notes |
|-------|--------|-------|
| libelle | plats.libelle | Tagine, Couscous, etc. |
| stock | plats.stock | « disponible » ou « épuisé » |
| image | plats.image | chemin storage/public |
| catégorie | categories.type | Entrées, Plats principaux… |
| thématique | thematiques.region | Fès, Marrakech… |
| prix | prix.montant | decimal |
| durée | temp_preparations.duree | minutes |
| ingrédients | ingrediants | relation many-to-many ou pivot |

## Étapes

1. Compléter modèle `Plats` : relations + `$fillable`
2. Implémenter `PlatsController` (resource)
3. Form Request `StorePlatRequest` / `UpdatePlatRequest`
4. Vues admin : index (tableau), create/edit (formulaire), show
5. Upload image : `Storage::disk('public')` → `storage/app/public/plats/`
6. Route groupe `/admin/plats` avec middleware auth si disponible

## Validation

```php
'libelle' => 'required|string|max:255',
'stock' => 'required|in:disponible,épuisé',
'image' => 'nullable|image|max:2048',
'montant' => 'required|numeric|min:0',
```

## Toggle stock rapide

Action `PATCH /admin/plats/{plat}/stock` pour basculer disponible ↔ épuisé sans formulaire complet.
