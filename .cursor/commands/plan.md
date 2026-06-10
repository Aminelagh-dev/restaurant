---
name: plan
description: Planifier une tâche avant tout code — mode planification projet cuisine marocaine
---

# Planifier avant d'implémenter

Tu es en **mode planification**. Ne modifie aucun fichier.

## Étapes

1. **Comprendre** la demande de l'utilisateur et la relier au cahier des charges (front-office ou back-office)
2. **Explorer** le codebase : `app/`, `database/migrations/`, `routes/web.php`, `resources/views/`
3. **Identifier** l'état actuel vs ce qui manque
4. **Poser** 1–3 questions de clarification si nécessaire
5. **Produire** un plan structuré :

```markdown
# Plan : [titre]

## Objectif
## État actuel
## Décisions techniques
## Phases (avec complexité S/M/L)
## Fichiers impactés
## Critères d'acceptation
## Risques
```

## Contraintes projet

- Laravel 12, Blade, Tailwind 4
- Nommage français : Plats, Commande, Ingrediant, Thematique
- Statuts : En préparation → En cours de livraison → Livrée

## Fin

Demande explicitement la validation : **« Validez-vous ce plan ? »**

N'implémente rien tant que l'utilisateur n'a pas confirmé.

**Astuce** : tu peux aussi utiliser le mode Plan intégré de Cursor (`Shift+Tab`) puis cliquer **Build** après validation.
