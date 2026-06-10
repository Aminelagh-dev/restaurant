---
name: plan-feature
description: Plan détaillé pour une nouvelle fonctionnalité (multi-fichiers)
---

# Plan Feature

Planifie l'implémentation de la fonctionnalité demandée par l'utilisateur.

## Processus

1. Lire `AGENTS.md` et les rules `.cursor/rules/`
2. Déléguer au subagent `planner` si l'analyse codebase est large
3. Cartographier les dépendances : migrations → models → controllers → routes → vues

## Plan obligatoire

| Section | Contenu |
|---------|---------|
| Contexte | Lien cahier des charges |
| Prérequis | Migrations/FK manquantes |
| Phase 1 | Backend (fichiers nommés) |
| Phase 2 | Frontend (vues nommées) |
| Phase 3 | Tests & seeders |
| Acceptance | Checklist testable |

## Estimation

- **S** : < 1h, 1–3 fichiers
- **M** : 1–3h, 4–10 fichiers
- **L** : > 3h, refactor ou 10+ fichiers

## Règles

- Ne pas coder
- Proposer sauvegarde dans `.cursor/plans/YYYY-MM-DD-{feature}.md` si l'utilisateur le souhaite
- Attendre validation avant implémentation

Après validation, suggérer `/build-feature` ou passer en mode Agent.
