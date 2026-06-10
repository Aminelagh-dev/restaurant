---
name: implement-feature
description: Implémenter une fonctionnalité Laravel de bout en bout (migration, model, controller, routes, vues). Utiliser quand l'utilisateur demande d'ajouter ou compléter une feature du front-office ou back-office.
paths:
  - "app/**"
  - "resources/**"
  - "routes/**"
  - "database/**"
---

# Implémenter une fonctionnalité

## Prérequis

1. Lire `AGENTS.md` et les rules `.cursor/rules/`
2. Identifier si la demande concerne front-office, back-office ou les deux
3. Vérifier l'état actuel des controllers/models concernés

## Workflow

### 1. Analyse (ne pas coder encore si ambigu)

- Lister les fichiers à créer/modifier
- Vérifier les migrations existantes vs besoins (FK manquantes ?)
- Confirmer les noms français existants

### 2. Backend

```
database/migrations/   → nouvelle migration si schéma incomplet
app/Models/            → $fillable, relations, casts
app/Http/Requests/     → validation (si formulaire)
app/Http/Controllers/  → logique métier
routes/web.php         → routes nommées
```

### 3. Frontend

```
resources/views/layouts/     → layout client ou admin
resources/views/{module}/    → vues Blade + Tailwind
```

### 4. Données de test

- Seeder ou factory si utile pour démo
- `php artisan migrate` si nouvelle migration

### 5. Vérification

```bash
composer test
php artisan route:list --path={prefix}
```

## Checklist par module

| Module | Fichiers typiques |
|--------|-------------------|
| Menu | PlatsController@index, views/menu/ |
| Panier | session panier, PanierController |
| Commande | CommandeController@store, Client |
| Admin CRUD plat | PlatsController resource, views/admin/plats/ |
| Suivi | CommandeController@show, badge statut |
| Dashboard | requêtes agrégées, views/admin/dashboard |

## Principes

- Scope minimal : uniquement ce qui est demandé
- Réutiliser les controllers existants, ne pas en dupliquer
- Messages utilisateur en français
