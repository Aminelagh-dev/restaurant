---
name: implement-crud
description: Implémenter un CRUD Laravel complet pour une entité métier
---

# Implémenter CRUD

Implémente un CRUD resource Laravel pour l'entité demandée (Plats, Categorie, Thematique, Commande, etc.).

## Template

Pour l'entité `{Entity}` :

1. **Model** `app/Models/{Entity}.php` — fillable, relations
2. **Controller** `app/Http/Controllers/{Entity}Controller.php` — 7 méthodes resource
3. **Requests** `Store{Entity}Request`, `Update{Entity}Request`
4. **Routes** `Route::resource('admin/{entities}', {Entity}Controller::class)`
5. **Vues** `resources/views/admin/{entities}/` — index, create, edit, show

## Spécificités entités

| Entité | Particularité |
|--------|---------------|
| Plats | upload image, toggle stock, relations prix/catégorie |
| Commande | lecture + update statut seulement (pas delete client) |
| Categorie | types fixes suggérés en select |
| Thematique | régions marocaines en select |

## Skill associée

Suivre `.cursor/skills/add-plat-crud/SKILL.md` si entité = Plats.

Après implémentation, lister les routes créées et comment tester.
