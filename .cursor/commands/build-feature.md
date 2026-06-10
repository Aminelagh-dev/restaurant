---
name: build-feature
description: Implémenter une feature après validation du plan
---

# Build Feature

Implémente la fonctionnalité **après plan validé** (ou si la demande est simple et claire).

## Avant de coder

- Confirmer que un plan existe ou que le scope est trivial (< 3 fichiers)
- Lire les rules `.cursor/rules/` applicables
- Vérifier les migrations et modèles existants

## Ordre d'implémentation

1. Migration (si schéma incomplet)
2. Model (relations, fillable)
3. Form Request (validation)
4. Controller
5. Routes nommées
6. Vues Blade + Tailwind
7. Seeder si utile

## Délégation subagents

- Backend complexe → `laravel-backend`
- UI → `frontend-ui`
- Après livraison → `verifier`

## Fin de tâche

```bash
composer test
php artisan route:list --path={prefix}
```

Résumer : fichiers modifiés, comment tester manuellement, points restants éventuels.
