---
name: plan-frontoffice
description: Planifier les features client — menu, panier, commande, suivi
---

# Plan Front-Office (Client)

Planifie une feature **interface client** sans écrire de code.

## Périmètre front-office

| Feature | Description | Fichiers typiques |
|---------|-------------|-------------------|
| Menu | Liste plats par catégorie, visuel | `PlatsController@index`, `views/menu/` |
| Détail plat | Ingrédients, temps, prix | `PlatsController@show`, `views/plats/show` |
| Panier | Multi-plats, quantités, total | `PanierController`, session, `views/panier/` |
| Commande | Adresse + destinataire | `CommandeController@store`, `Client` |
| Suivi | 3 statuts livraison | `CommandeController@show`, badges/timeline |

## Analyse requise

1. Quelle feature exacte ? (menu / panier / commande / suivi / tout le parcours)
2. Session panier ou persistance DB ?
3. Layout client existant ou à créer ?

## Plan attendu

Pour chaque écran :
- Route + nom
- Controller + méthodes
- Vue Blade + composants
- Données Eloquent nécessaires (eager loading)
- Règles métier (stock épuisé, validation formulaire)

## UX prioritaire

- Images plats en vedette
- Mobile-first
- Feedback visuel (panier mis à jour, confirmation commande)

**Attendre validation utilisateur avant implémentation.**
