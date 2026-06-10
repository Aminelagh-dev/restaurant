---
name: plan-backoffice
description: Planifier l'admin — CRUD carte, catégories, commandes, dashboard
---

# Plan Back-Office (Gérant)

Planifie une feature **interface administration** sans coder.

## Périmètre back-office

| Module | Actions | Controller |
|--------|---------|------------|
| Carte (CRUD plats) | create, edit, delete, toggle stock | `PlatsController` |
| Catégories | CRUD types (Entrées, Desserts…) | `CategorieController` |
| Thématiques | CRUD régions (Fès, Marrakech…) | `ThematiqueController` |
| Commandes | Liste chronologique, changer statut | `CommandeController` |
| Dashboard | Top plats, CA quotidien | nouveau ou méthode dédiée |

## Analyse requise

1. Quel module admin ?
2. Auth gérant : middleware `auth` sur routes `/admin/*` ?
3. Upload images plats : `storage/app/public/plats/`

## Plan attendu

- Préfixe routes `/admin/...`
- Layout `layouts/admin.blade.php` avec sidebar
- Tableaux avec actions (edit, delete, toggle stock)
- Dashboard : requêtes SQL/Eloquent pour agrégations

### Dashboard — métriques

```sql
-- CA quotidien : SUM(quantite * prix_unitaire) WHERE date = today
-- Top plats : GROUP BY plat_id ORDER BY COUNT DESC LIMIT 5
```

## Sécurité

- Routes admin protégées
- Validation côté serveur sur tous les formulaires
- Pas d'exposition des endpoints admin sans auth

**Attendre validation avant implémentation.**
