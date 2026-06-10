---
name: design-plan
description: Produire un plan d'implémentation structuré avant tout code. Utiliser pour nouvelles features, refactors multi-fichiers ou décisions d'architecture.
disable-model-invocation: false
---

# Concevoir un plan d'implémentation

## Quand utiliser

- Feature touchant 3+ fichiers
- Ambiguïté sur schéma DB ou parcours utilisateur
- Demande explicite de planifier (`/plan-feature`, mode Plan)

## Format du plan

```markdown
# Plan : [Titre feature]

## Contexte
[1-2 phrases — lien avec le cahier des charges]

## Prérequis / état actuel
[Fichiers existants, lacunes identifiées]

## Décisions
[Choix techniques avec justification courte]

## Étapes
### Phase 1 — [nom] (complexité: S/M/L)
- [ ] Tâche concrète → fichier(s) cible(s)

### Phase 2 — ...
...

## Fichiers impactés
| Action | Fichier |
|--------|---------|
| créer | ... |
| modifier | ... |

## Tests / validation
- [ ] Critères vérifiables

## Risques
- [Risque] → mitigation
```

## Règles

1. **Ne pas coder** tant que l'utilisateur n'a pas validé le plan
2. Référencer les entités françaises existantes (`Plats`, `Commande`, etc.)
3. Estimer S (< 1h), M (1-3h), L (> 3h) par phase
4. Prioriser : migration → model → controller → routes → vues
5. Proposer de sauvegarder le plan dans `.cursor/plans/` si pertinent

## Modules projet — rappel scope

| Zone | Features |
|------|----------|
| Front-office | menu, détail, panier, commande, suivi |
| Back-office | CRUD plats, catégories, thématiques, commandes, dashboard |
