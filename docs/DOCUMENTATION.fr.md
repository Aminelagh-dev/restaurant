# Riad Saveurs — Documentation complète

> **Plateforme de commande en ligne — Cuisine marocaine traditionnelle**
> Application web Laravel 12 permettant aux clients de découvrir et commander des plats
> marocains (Harira, Tagines, Couscous, Pastilla, Rfissa…), avec un back-office complet
> pour le gérant du restaurant.

| | |
|---|---|
| **Nom du projet** | Riad Saveurs |
| **Type** | Application web de commande de restauration (front-office + back-office) |
| **Version de la stack** | Laravel 12 · PHP 8.2+ · Vite 7 · Tailwind CSS 4 |
| **Base de données** | SQLite (par défaut) ou MySQL |
| **Langue de l'interface** | Français |
| **Date du document** | 13 juin 2026 |

---

## Table des matières

1. [Présentation](#1-présentation)
2. [Toutes les informations sur le site](#2-toutes-les-informations-sur-le-site)
   - 2.1 [Pile technique](#21-pile-technique)
   - 2.2 [Architecture générale](#22-architecture-générale)
   - 2.3 [Modèle de données](#23-modèle-de-données)
   - 2.4 [Cartographie des routes](#24-cartographie-des-routes)
   - 2.5 [Front-office (espace client)](#25-front-office-espace-client)
   - 2.6 [Back-office (espace gérant)](#26-back-office-espace-gérant)
   - 2.7 [Règles métier importantes](#27-règles-métier-importantes)
   - 2.8 [Sécurité](#28-sécurité)
   - 2.9 [Interface et design](#29-interface-et-design)
3. [Installation et exploitation](#3-installation-et-exploitation)
4. [Rapport technique](#4-rapport-technique)
   - 4.1 [Travail réalisé](#41-travail-réalisé)
   - 4.2 [Choix techniques et justifications](#42-choix-techniques-et-justifications)
   - 4.3 [Mesures de sécurité mises en place](#43-mesures-de-sécurité-mises-en-place)
   - 4.4 [Points d'attention et limites connues](#44-points-dattention-et-limites-connues)
   - 4.5 [Améliorations futures recommandées](#45-améliorations-futures-recommandées)
   - 4.6 [Synthèse](#46-synthèse)

---

## 1. Présentation

**Riad Saveurs** est une plateforme web qui met en valeur la cuisine marocaine
traditionnelle et permet à un client de commander des plats en quelques étapes,
sans avoir besoin de créer un compte.

Le site se compose de deux espaces :

- **L'espace client (front-office)** — une vitrine gastronomique élégante où le
  visiteur parcourt la carte par catégories, consulte le détail d'un plat
  (ingrédients, temps de préparation, prix), remplit un panier, passe commande en
  renseignant une adresse de livraison, puis suit l'avancement de sa commande.

- **L'espace gérant (back-office)** — un tableau de bord d'administration où le
  gérant gère la carte (plats, prix, ruptures de stock), les catégories, les
  clients et les commandes, et visualise des indicateurs d'activité (chiffre
  d'affaires, plats les plus commandés, répartition des commandes par statut).

### Objectifs du produit

- Offrir une **expérience client fluide** : pas d'inscription obligatoire, parcours
  en 4 étapes (menu → panier → commande → suivi).
- Donner au gérant un **outil de gestion autonome** de toute la carte et des
  commandes, en temps réel.
- Mettre en avant le **patrimoine culinaire marocain** par des visuels soignés et
  des couleurs chaudes (terracotta, safran, ocre).

### Public visé

- **Clients** : amateurs de cuisine marocaine souhaitant commander en livraison.
- **Gérant / personnel du restaurant** : gestion de la carte et suivi des commandes.

---

## 2. Toutes les informations sur le site

### 2.1 Pile technique

| Couche | Technologie | Rôle |
|--------|-------------|------|
| Backend | **PHP 8.2+**, **Laravel 12**, Eloquent ORM | Logique métier, routage, persistance |
| Frontend | **Blade**, **Vite 7**, **Tailwind CSS 4** | Rendu des vues, compilation des assets |
| Base de données | **SQLite** (défaut) / MySQL | Stockage des données |
| Sessions / Panier | Driver `database` | Panier et autorisations stockés en session |
| Tests | **PHPUnit 11** | Tests automatisés |
| Outils dev | Laravel Pail (logs), Pint (formatage), Sail | Confort de développement |

### 2.2 Architecture générale

L'application suit l'architecture **MVC** standard de Laravel, avec une séparation
nette entre front-office et back-office.

```
app/
  Http/
    Controllers/
      MenuController.php        # Carte + détail d'un plat (client)
      PanierController.php      # Panier en session (client)
      CheckoutController.php    # Passage de commande (client)
      SuiviController.php       # Suivi de commande (client)
      Admin/
        DashboardController.php # Tableau de bord (gérant)
        PlatsController.php     # CRUD carte
        CategorieController.php # CRUD catégories
        ClientController.php    # CRUD clients
        CommandeController.php  # Gestion des commandes + statuts
    Requests/                   # Form Requests (validation)
      CheckoutRequest.php
      StorePlatRequest.php
      UpdatePlatRequest.php
  Models/                       # Plats, Categorie, Client, Commande, CommandePlat, User
  Support/
    Panier.php                  # Service panier (session)
database/
  migrations/                   # Schéma métier
  seeders/                      # Données de démonstration (carte + commandes)
resources/
  views/                        # Vues Blade (front + admin + composants)
  css/app.css · js/app.js       # Assets compilés par Vite
routes/web.php                  # Toutes les routes HTTP
public/images/plats/            # Photos des plats
```

### 2.3 Modèle de données

Six tables métier structurent l'application.

#### `categories`
| Colonne | Type | Notes |
|---------|------|-------|
| id | bigint (PK) | |
| nom | string | Ex. « Entrées », « Plats principaux » |
| description | text (nullable) | |
| timestamps | | |

#### `plats`
| Colonne | Type | Notes |
|---------|------|-------|
| id | bigint (PK) | |
| categorie_id | FK → categories | `cascadeOnDelete` |
| nom | string | |
| description | text | |
| ingredients | text | Liste textuelle |
| temps_preparation | integer | Minutes |
| prix | decimal(10,2) | En DH |
| image | string (nullable) | URL absolue **ou** chemin local |
| stock | integer | Défaut 0 |
| disponible | boolean | Défaut `true` |
| timestamps | | |

#### `clients`
| Colonne | Type | Notes |
|---------|------|-------|
| id | bigint (PK) | |
| nom, prenom | string | |
| telephone | string | Sert de clé d'unicité au checkout |
| email | string (nullable) | |
| timestamps | | |

#### `commandes`
| Colonne | Type | Notes |
|---------|------|-------|
| id | bigint (PK) | Sert de « numéro de commande » |
| client_id | FK → clients | `cascadeOnDelete` |
| date_commande | dateTime | |
| montant_total | decimal(10,2) | |
| adresse_livraison | string | |
| nom_recepteur | string | Destinataire de la livraison |
| telephone_recepteur | string | Sert au suivi de commande |
| statut | enum | `en_preparation` · `en_livraison` · `livree` |
| timestamps | | |

#### `commande_plat` (lignes de commande)
| Colonne | Type | Notes |
|---------|------|-------|
| id | bigint (PK) | Vraie table de lignes, pas un simple pivot |
| commande_id | FK → commandes | `cascadeOnDelete` |
| plat_id | FK → plats | `cascadeOnDelete` |
| quantite | integer | |
| prix_unitaire | decimal(10,2) | **Prix figé** au moment de la commande |
| sous_total | decimal(10,2) | |
| unique(commande_id, plat_id) | | Un plat n'apparaît qu'une fois par commande |

#### `users` (comptes gérant)
| Colonne | Type | Notes |
|---------|------|-------|
| id | bigint (PK) | |
| nom | string | |
| prenom | string (nullable) | |
| email | string (unique) | |
| telephone | string (nullable) | |
| password | string (hashed) | |
| role | enum | `client` · `admin` |

#### Relations Eloquent

- `Categorie` **hasMany** `Plats`
- `Plats` **belongsTo** `Categorie` ; **belongsToMany** `Commande` (via `commande_plat`)
- `Client` **hasMany** `Commande`
- `Commande` **belongsTo** `Client` ; **hasMany** `CommandePlat` (lignes) ; **belongsToMany** `Plats`
- `CommandePlat` **belongsTo** `Commande` et `Plats`

### 2.4 Cartographie des routes

#### Front-office (espace client)

| Méthode | URL | Nom | Action |
|---------|-----|-----|--------|
| GET | `/` | `menu.index` | Carte par catégories (+ recherche `?q=`) |
| GET | `/plats/{plat}` | `menu.show` | Détail d'un plat + plats similaires |
| GET | `/panier` | `panier.index` | Affiche le panier |
| POST | `/panier/{plat}` | `panier.store` | Ajoute un plat au panier |
| PATCH | `/panier/{plat}` | `panier.update` | Modifie la quantité |
| DELETE | `/panier/{plat}` | `panier.destroy` | Retire un plat |
| DELETE | `/panier` | `panier.clear` | Vide le panier |
| GET | `/commander` | `checkout.create` | Formulaire de commande |
| POST | `/commander` | `checkout.store` | Enregistre la commande |
| GET | `/suivi` | `suivi.index` | Formulaire de recherche de commande |
| POST | `/suivi` | `suivi.search` | Recherche (throttle 10/min) |
| GET | `/suivi/{commande}` | `suivi.show` | Statut détaillé (accès contrôlé) |

#### Back-office (préfixe `/admin`)

| Méthode | URL | Nom | Action |
|---------|-----|-----|--------|
| GET | `/admin` | `admin.dashboard` | Tableau de bord |
| GET/POST/… | `/admin/plats` | `admin.plats.*` | CRUD carte (sauf `show`) |
| GET/POST/… | `/admin/categories` | `admin.categories.*` | CRUD catégories (sauf `show`) |
| GET | `/admin/commandes` | `admin.commandes.index` | Liste chronologique (filtre statut) |
| GET | `/admin/commandes/{commande}` | `admin.commandes.show` | Détail d'une commande |
| PATCH | `/admin/commandes/{commande}/statut` | `admin.commandes.statut` | Change le statut |
| GET/POST/… | `/admin/clients` | `admin.clients.*` | CRUD clients (sauf `show`) |

### 2.5 Front-office (espace client)

**Parcours en 4 étapes :**

1. **Menu gastronomique** — Les plats sont groupés par catégorie. Une barre de
   recherche filtre par nom ou description. Les plats disponibles sont affichés en
   priorité (`orderByDesc('disponible')`). Les catégories vides sont masquées.

2. **Détail d'un repas** — Affiche la description, la liste des ingrédients, le
   temps de préparation, le prix et jusqu'à 3 plats similaires de la même catégorie.
   Le sélecteur de quantité permet d'ajouter au panier.

3. **Panier (en session)** — Le panier est stocké côté serveur en session sous la
   forme `[plat_id => quantité]` via le service `App\Support\Panier`. Le client peut
   modifier les quantités (avec auto-soumission), retirer un plat ou vider le panier.
   Le total est recalculé à partir du prix actuel des plats.

4. **Passage de commande** — Le formulaire demande l'identité du client, l'adresse
   de livraison et les coordonnées du destinataire. À la validation :
   - Le panier est revérifié (non vide, plats toujours disponibles).
   - Un `Client` est créé ou retrouvé par son **numéro de téléphone** (`firstOrCreate`).
   - La commande et ses lignes sont enregistrées dans une **transaction**.
   - Le **stock de chaque plat est décrémenté** ; un plat dont le stock tombe à 0
     est automatiquement marqué indisponible.
   - Le client est redirigé vers le suivi de sa commande.

5. **Suivi de commande** — Le client recherche sa commande via le **numéro** +
   le **téléphone du destinataire**. L'accès au détail est protégé : seules les
   commandes « autorisées » dans la session (après paiement ou recherche réussie)
   sont consultables ; toute autre tentative renvoie une erreur **403**.

### 2.6 Back-office (espace gérant)

- **Tableau de bord** (`DashboardController`) — Indicateurs clés :
  - Nombre de plats, plats épuisés, catégories, clients, commandes.
  - **Chiffre d'affaires du jour** et **chiffre d'affaires total**.
  - **Top 5 des plats** les plus commandés (quantité + CA).
  - **CA des 7 derniers jours** (série temporelle).
  - Répartition des commandes par statut.
  - 6 dernières commandes.

- **Gestion de la carte** (`PlatsController`) — CRUD complet des plats avec recherche
  et filtre par catégorie, pagination (12/page). L'image peut être fournie par
  **upload de fichier** ou par **URL**. Un plat figurant déjà dans des commandes ne
  peut pas être supprimé (préservation de l'historique) : on invite à le marquer
  « Épuisé ».

- **Gestion des catégories** (`CategorieController`) — CRUD ; une catégorie contenant
  des plats ne peut pas être supprimée.

- **Gestion des clients** (`ClientController`) — CRUD avec recherche multi-champs
  (nom, prénom, téléphone, email) et compteur de commandes. Email unique.

- **Gestion des commandes** (`CommandeController`) — Liste chronologique paginée
  (15/page), filtrable par statut, détail d'une commande, et **changement de statut**
  en temps réel (En préparation → En cours de livraison → Livrée).

### 2.7 Règles métier importantes

- **Prix figés** : à la commande, le `prix_unitaire` est copié dans la ligne de
  commande. Une modification ultérieure du prix d'un plat n'altère pas les commandes
  passées.
- **Gestion du stock** : décrément automatique à la commande ; passage en
  « indisponible » dès que le stock atteint 0. Un plat est considéré **épuisé** s'il
  est marqué indisponible **ou** si son stock est ≤ 0 (`Plats::estEpuise()`).
- **Client unique par téléphone** : le `firstOrCreate` évite les doublons clients.
- **Intégrité de l'historique** : impossible de supprimer un plat déjà commandé ou
  une catégorie non vide.
- **Statut protégé** : la colonne `statut` (commande) et `role` (utilisateur) sont
  volontairement exclues du `$fillable` et affectées explicitement (anti
  mass-assignment).

### 2.8 Sécurité

Mesures effectivement présentes dans le code :

- **Protection CSRF** sur tous les formulaires (`@csrf`).
- **Anti mass-assignment** : `statut` et `role` hors `$fillable`, affectés
  explicitement.
- **Validation systématique** via Form Requests et `validate()` (types, longueurs,
  existence des clés étrangères, format email, URL d'image…).
- **Upload d'image sécurisé** : l'extension est déduite du **type MIME réel** vérifié
  côté serveur (jamais du nom de fichier fourni par le client) ; seuls
  jpeg/png/webp/gif sont acceptés (≤ 4 Mo).
- **Contrôle d'accès au suivi** : une commande n'est consultable que si elle a été
  « autorisée » en session ; sinon **403**.
- **Limitation de débit** (`throttle:10,1`) sur la recherche de commande pour limiter
  l'énumération.
- **Hachage des mots de passe** (`password` casté en `hashed`).

> ⚠️ Un point de sécurité important concernant le back-office est détaillé dans le
> [rapport technique, §4.4](#44-points-dattention-et-limites-connues).

### 2.9 Interface et design

- **Identité visuelle** : marque « Riad Saveurs », palette de couleurs chaudes
  marocaines (terracotta, safran, ocre), typographie Manrope.
- **Thème clair / sombre** : bascule avec mémorisation dans `localStorage` et
  application avant rendu pour éviter le flash.
- **Composant d'icônes** : `<x-icon>` — bibliothèque SVG inline maison (plus, minus,
  cart, clock…).
- **Responsive** : navigation supérieure avec compteur de panier dynamique.
- **Layouts séparés** : `layouts/app.blade.php` (front) et `layouts/admin.blade.php`
  (back-office avec barre latérale).

---

## 3. Installation et exploitation

### Prérequis
- PHP **8.2+** (testé sur 8.3)
- Composer 2
- Node.js 18+ et npm

### Installation

```bash
# 1. Dépendances PHP
composer install

# 2. Dépendances front + binaire esbuild
npm install
npm rebuild esbuild        # si le post-install n'a pas été exécuté

# 3. Configuration
cp .env.example .env        # si .env absent
php artisan key:generate

# 4. Base de données (SQLite par défaut)
#    Créer le fichier database/database.sqlite puis :
php artisan migrate

# 5. (Optionnel) Données de démonstration
php artisan db:seed
```

### Lancement en développement

```bash
composer dev      # serveur + queue + logs + Vite (tout-en-un)
# — ou séparément —
php artisan serve # http://127.0.0.1:8000
npm run dev       # Vite (HMR) sur http://localhost:5173
```

### Compte de démonstration (après `db:seed`)
- **Gérant** : `admin@riad.test` / `password`
- Données : carte marocaine complète (Harira, Tagines, Couscous, Pastilla,
  Desserts, Thés/Jus), 5 clients fictifs et des commandes réparties sur 7 jours.

### Tests

```bash
composer test     # PHPUnit
```

---

## 4. Rapport technique

### 4.1 Travail réalisé

L'application livrée couvre **l'intégralité du périmètre fonctionnel** décrit dans le
cahier des charges :

| Domaine | Statut |
|---------|--------|
| Menu gastronomique par catégories + recherche | ✅ Réalisé |
| Détail d'un plat (ingrédients, temps, prix, similaires) | ✅ Réalisé |
| Panier en session (multi-plats, quantités, total) | ✅ Réalisé |
| Passage de commande (adresse + destinataire) | ✅ Réalisé |
| Suivi de commande par statut | ✅ Réalisé |
| CRUD carte (plats, prix, rupture de stock, images) | ✅ Réalisé |
| CRUD catégories | ✅ Réalisé |
| CRUD clients | ✅ Réalisé |
| Gestion des commandes + changement de statut | ✅ Réalisé |
| Tableau de bord (plats populaires, CA quotidien) | ✅ Réalisé |
| Données de démonstration (carte + commandes) | ✅ Réalisé |
| Thème clair/sombre, design responsive | ✅ Réalisé |

### 4.2 Choix techniques et justifications

- **Panier en session plutôt qu'en base** — Le client n'a pas besoin de compte ; le
  panier est éphémère et propre à la session, ce qui simplifie le parcours et évite
  une table supplémentaire.

- **`commande_plat` comme vraie table de lignes** (avec clé primaire et timestamps),
  et non simple pivot — Permet de **figer le prix unitaire** au moment de la commande,
  garantissant un historique fidèle même si la carte évolue.

- **Transaction au checkout** — La création de la commande, de ses lignes et la
  décrémentation du stock se font de façon **atomique** : aucune commande partielle
  en cas d'erreur.

- **`firstOrCreate` du client par téléphone** — Évite la prolifération de doublons
  clients tout en gardant un parcours sans inscription.

- **Suppression défensive** — Un plat commandé ou une catégorie non vide ne peuvent
  être supprimés, pour préserver l'intégrité référentielle et l'historique.

- **SQLite par défaut** — Démarrage immédiat sans serveur de base de données ;
  migration vers MySQL possible par simple changement de configuration.

### 4.3 Mesures de sécurité mises en place

Récapitulatif des protections implémentées (détaillées au §2.8) :

1. Protection CSRF sur tous les formulaires.
2. Protection contre le mass-assignment des colonnes sensibles (`statut`, `role`).
3. Validation stricte des entrées (Form Requests + règles).
4. Upload d'image validé par type MIME réel, taille et format restreints.
5. Contrôle d'accès au suivi de commande via autorisation en session (403 sinon).
6. Limitation de débit sur la recherche de commande.
7. Hachage automatique des mots de passe.

### 4.4 Points d'attention et limites connues

- **⚠️ Le back-office `/admin` n'est pas protégé par authentification.** Le modèle
  `User` et les rôles (`client`/`admin`) existent et un compte gérant est créé au
  seed, mais **aucun middleware `auth` ni contrôle de rôle n'est appliqué** aux routes
  `/admin` dans `routes/web.php`. En l'état, n'importe quel visiteur peut accéder au
  tableau de bord et aux opérations CRUD. **C'est la priorité n°1 à corriger avant
  toute mise en production** (voir §4.5).

- **Incohérence de nommage du modèle `Plat`** — Le fichier `app/Models/Plats.php`
  contenait initialement une classe `Plat` (non conforme PSR-4, ignorée par
  l'autoloader). La classe utilisée par l'application est `Plats`. À harmoniser pour
  éviter toute confusion.

- **Vulnérabilités npm** — `npm audit` signale des vulnérabilités dans la chaîne
  d'outils de développement (Vite). Sans impact sur la production (dépendances de
  build), mais à surveiller.

- **Pas de gestion de paiement en ligne** — La commande est enregistrée sans
  encaissement (paiement supposé à la livraison).

- **Pas d'espace client authentifié** — Le suivi repose sur numéro + téléphone, sans
  historique de compte.

### 4.5 Améliorations futures recommandées

| Priorité | Amélioration |
|----------|--------------|
| 🔴 Haute | **Protéger `/admin`** par middleware `auth` + contrôle de rôle `admin`, et ajouter un écran de connexion gérant. |
| 🟠 Moyenne | Harmoniser le nommage du modèle `Plats`/`Plat` (PSR-4). |
| 🟠 Moyenne | Notifications client (email/SMS) aux changements de statut. |
| 🟡 Basse | Paiement en ligne. |
| 🟡 Basse | Espace client authentifié avec historique de commandes. |
| 🟡 Basse | Suite de tests automatisés (front + back) plus étoffée. |
| 🟡 Basse | Mise à jour des dépendances npm signalées par `npm audit`. |

### 4.6 Synthèse

Riad Saveurs est une application Laravel 12 **fonctionnellement complète** et bien
structurée, couvrant l'ensemble du parcours client (menu → panier → commande → suivi)
et de la gestion gérant (carte, catégories, clients, commandes, tableau de bord). Le
code applique de bonnes pratiques (transactions, prix figés, protection
mass-assignment, validation, upload sécurisé, suppression défensive).

Le **seul point bloquant pour une mise en production** est l'**absence
d'authentification sur le back-office** : il convient de protéger les routes `/admin`
avant tout déploiement. Une fois ce point traité, l'application est prête à être
exploitée.

---

*Document généré le 13 juin 2026 — Riad Saveurs.*
