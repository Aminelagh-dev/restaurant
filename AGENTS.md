# Plateforme de Commande — Cuisine Marocaine Traditionnelle

Application web Laravel 12 permettant aux clients de découvrir et commander des plats marocains (Tagines, Couscous, Pastillas, Rfissa, etc.) avec un back-office pour le gérant.

## Stack technique

| Couche | Technologie |
|--------|-------------|
| Backend | PHP 8.2+, Laravel 12, Eloquent |
| Frontend | Blade, Vite 7, Tailwind CSS 4 |
| Base de données | MySQL/SQLite (migrations Laravel) |
| Tests | PHPUnit |

## Structure du dépôt

```
app/
  Http/Controllers/   # PlatsController, CommandeController, CategorieController, etc.
  Models/             # Plat, Commande, Client, Categorie, Thematique, Statut, etc.
database/migrations/  # Schéma métier (plats, commandes, catégories, thématiques…)
resources/views/      # Vues Blade (front-office + back-office)
routes/web.php        # Routes HTTP
```

## Domaine métier

### Entités principales

- **Plats** — libellé, disponibilité (disponible/épuisé), image
- **Categories** — type (Entrées, Plats principaux, Desserts, Thés)
- **Thematiques** — région (Fès, Marrakech, etc.)
- **Ingrediants** — produits associés aux plats
- **TempPreparation** — durée estimée de préparation
- **Prix** — montant décimal
- **Clients** — nom, prénom, email
- **Commandes** — adresse_livraison, statut
- **Commandes_plats** — pivot commande ↔ plats (quantités)
- **Statuts** — En préparation, En cours de livraison, Livrée

### Front-Office (client)

1. Menu gastronomique par catégories
2. Détail repas (ingrédients, temps, prix)
3. Panier (multi-plats, quantités, total)
4. Passage de commande (adresse + destinataire)
5. Suivi de commande par statut

### Back-Office (gérant)

1. CRUD carte (plats, prix, disponibilité)
2. Gestion catégories et thématiques régionales
3. Gestion commandes (liste chronologique, changement de statut)
4. Tableau de bord (plats populaires, CA quotidien)

## Conventions de code

- Conserver les noms français existants (`Plat`, `Ingrediant`, `Commande`, `Thematique`)
  — le modèle des plats est singulier (`App\Models\Plat`, table `plats`), comme les autres modèles
- Ne pas renommer les tables/colonnes sans migration explicite
- Controllers en ressource Laravel (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`)
- Validation via Form Requests quand la logique dépasse 2–3 règles
- UI : mettre en avant les visuels des plats (images, cartes, couleurs chaudes marocaines)

## Commandes de développement

```bash
composer dev          # serveur + queue + logs + vite
php artisan migrate   # appliquer les migrations
composer test         # PHPUnit
npm run dev           # Vite seul
```

## Cursor — outils du projet

| Type | Emplacement | Usage |
|------|-------------|-------|
| Rules | `.cursor/rules/` | Standards code et domaine |
| Skills | `.cursor/skills/` | Workflows multi-étapes |
| Subagents | `.cursor/agents/` | Spécialistes (plan, backend, UI, vérif) |
| Commands | `.cursor/commands/` | Slash commands dont `/plan-*` |

Pour planifier une fonctionnalité : `/plan-feature` ou le mode Plan intégré (`Shift+Tab`).
