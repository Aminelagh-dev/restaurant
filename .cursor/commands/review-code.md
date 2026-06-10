---
name: review-code
description: Revue de code — bugs, sécurité, conventions projet
---

# Code Review

Revue du code modifié ou du module demandé. **Ne pas modifier les fichiers.**

## Checklist

### Laravel
- [ ] `$fillable` / `$guarded` corrects
- [ ] Relations Eloquent définies
- [ ] Validation serveur sur formulaires
- [ ] `@csrf` dans vues POST
- [ ] Pas de N+1 (eager loading `with()`)

### Métier
- [ ] Statuts commande conformes
- [ ] Stock épuisé géré
- [ ] Pivot commandes_plats complet
- [ ] CA dashboard calcul correct

### UI
- [ ] Images plats affichées
- [ ] Responsive
- [ ] Messages français

### Sécurité
- [ ] Routes admin protégées
- [ ] Pas de mass assignment dangereux
- [ ] Upload images validé (type, taille)

## Format rapport

| Sévérité | Fichier | Problème | Suggestion |
|----------|---------|----------|------------|
| 🔴 / 🟡 / 🟢 | ... | ... | ... |

Déléguer à `verifier` pour confirmation fonctionnelle si besoin.
