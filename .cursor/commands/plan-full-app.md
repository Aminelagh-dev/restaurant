---
name: plan-full-app
description: Plan global de bout en bout — toute la plateforme cuisine marocaine
---

# Plan Application Complète

Produis un **plan de réalisation global** de la plateforme, phase par phase.

## Référence cahier des charges

### Front-office
- [ ] Menu gastronomique par catégories
- [ ] Détail repas (ingrédients, temps, prix)
- [ ] Panier multi-plats
- [ ] Passage commande (adresse + destinataire)
- [ ] Suivi commande (3 statuts)

### Back-office
- [ ] CRUD carte + rupture stock
- [ ] Gestion catégories et thématiques régionales
- [ ] Gestion commandes + changement statut
- [ ] Dashboard (top plats, CA quotidien)

## Méthode

1. Auditer l'état actuel du repo (migrations OK, controllers vides, routes minimales)
2. Proposer un **ordre de développement optimal** :
   - Phase 0 : Schéma DB complet + seeders
   - Phase 1 : Back-office CRUD (données avant UI client)
   - Phase 2 : Front-office menu + détail
   - Phase 3 : Panier + commande
   - Phase 4 : Suivi + dashboard
   - Phase 5 : Polish UI + tests
3. Estimer effort par phase (jours/homme indicatif)
4. Identifier dépendances bloquantes

## Format

Tableau récapitulatif + plan détaillé par phase avec fichiers.

**Ne pas implémenter.** Proposer de découper en sprints et de commencer par `/plan-database` ou `/plan-backoffice`.
