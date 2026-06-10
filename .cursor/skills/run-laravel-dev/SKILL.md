---
name: run-laravel-dev
description: Démarrer l'environnement de développement Laravel (serveur, migrations, Vite). Utiliser quand l'utilisateur veut lancer, tester ou configurer le projet localement.
disable-model-invocation: true
---

# Environnement de développement

## Setup initial (première fois)

```bash
composer install
cp .env.example .env   # si .env absent
php artisan key:generate
php artisan migrate
npm install
```

Configurer `.env` :
- `DB_CONNECTION=sqlite` + `DB_DATABASE=database/database.sqlite` (rapide)
- ou MySQL selon préférence

## Lancer tout (recommandé)

```bash
composer dev
```

Démarre : `php artisan serve`, queue, pail (logs), `npm run dev`

## Commandes utiles

```bash
php artisan migrate              # appliquer migrations
php artisan migrate:fresh --seed # reset + seed
php artisan route:list           # vérifier routes
php artisan storage:link         # images plats publiques
composer test                    # PHPUnit
php artisan pint                 # formatage PHP
```

## Vérification rapide

- Front : http://127.0.0.1:8000
- Vite HMR actif si `npm run dev` tourne
- Logs : terminal pail ou `storage/logs/laravel.log`

## Dépannage courant

| Problème | Solution |
|----------|----------|
| APP_KEY manquante | `php artisan key:generate` |
| Migration échoue | vérifier `.env` DB |
| Assets non chargés | `npm run build` ou `npm run dev` |
| 419 CSRF | `@csrf` dans formulaires Blade |
