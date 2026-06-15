# Riad Saveurs — Documentation complète

> **Plateforme de commande en ligne — Cuisine marocaine traditionnelle**
> Application web Laravel 12 permettant aux clients de découvrir et commander des plats
> marocains (Harira, Tagines, Couscous, Pastilla, Rfissa…), avec un back-office complet
> et **sécurisé** pour le gérant du restaurant, **disponible en français, anglais et arabe**.

| | |
|---|---|
| **Nom du projet** | Riad Saveurs |
| **Type** | Application web de commande de restauration (front-office + back-office) |
| **Version de la stack** | Laravel 12 · PHP 8.2+ · Vite 7 · Tailwind CSS 4 |
| **Base de données** | SQLite (par défaut) ou MySQL |
| **Langues de l'interface** | Français (défaut) · English · العربية (RTL) |
| **Authentification** | Back-office protégé (connexion + rôles `admin` et `operator`) |
| **Date du document** | 15 juin 2026 |

> **Mises à jour récentes intégrées dans cette version**
> 1. **Internationalisation (i18n)** — interface multilingue FR / EN / AR avec support
>    de l'écriture de droite à gauche (RTL) pour l'arabe.
> 2. **Sécurisation du back-office** — authentification gérant + contrôle de rôle
>    `admin` + écran de connexion (l'ancien accès `/admin` libre est corrigé).
> 3. **Navigation front-office** — bouton **Connexion** pour les visiteurs, **Espace
>    gérant** + **Déconnexion** pour un gérant connecté.
> 4. **Seeders idempotents** — plus aucun doublon de la carte lors d'un re-`seed`.
> 5. **Rôle `operator` (opérateur)** — nouveau profil de back-office limité à la **page
>    des commandes** : il consulte le statut courant et ne peut qu'**avancer la commande
>    d'un cran** (bouton « Marquer … »). Le gérant (`admin`) garde tous ses accès.
> 6. **Flux de statut affiné** — le gérant ne propose plus que les statuts **déjà
>    atteints + le tout premier suivant** ; un **retour en arrière purge l'historique**
>    des statuts postérieurs (la commande réapparaît « comme neuve » à cette étape).
> 7. **Badge de navigation** — le compteur de la barre latérale et la pastille de la
>    cloche signalent désormais les commandes **en attente** (et non plus en préparation).
> 8. **Panier en AJAX** — l'ajout au panier depuis le menu et le réglage des quantités
>    sur `/panier` se font **sans rechargement** : le compteur du panier, les sous-totaux
>    et le total se mettent à jour en direct (avec repli classique si JavaScript est absent).

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
   - 2.8 [Authentification et contrôle d'accès](#28-authentification-et-contrôle-daccès)
   - 2.9 [Internationalisation (FR / EN / AR)](#29-internationalisation-fr--en--ar)
   - 2.10 [Sécurité](#210-sécurité)
   - 2.11 [Interface et design](#211-interface-et-design)
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

- **L'espace gérant (back-office)** — un tableau de bord d'administration **protégé
  par authentification** où le gérant gère la carte (plats, prix, ruptures de stock),
  les catégories, les clients et les commandes, et visualise des indicateurs
  d'activité (chiffre d'affaires, plats les plus commandés, répartition par statut).

L'ensemble de l'interface est **multilingue** : français (par défaut), anglais et
arabe (avec mise en page de droite à gauche).

### Objectifs du produit

- Offrir une **expérience client fluide** : pas d'inscription obligatoire, parcours
  en 4 étapes (menu → panier → commande → suivi).
- Donner au gérant un **outil de gestion autonome et sécurisé** de toute la carte et
  des commandes, en temps réel.
- Rendre le service **accessible à un public international** (FR / EN / AR).
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
| Sessions / Panier | Driver `database` | Panier, langue et autorisations en session |
| Authentification | Garde `web` Laravel (Eloquent, `App\Models\User`) | Connexion gérant + rôle `admin` |
| Internationalisation | Traductions JSON Laravel (`__()`), middleware de locale | FR / EN / AR + RTL |
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
        AuthController.php      # Connexion / déconnexion gérant
        DashboardController.php # Tableau de bord (gérant)
        PlatsController.php     # CRUD carte
        CategorieController.php # CRUD catégories
        ClientController.php    # CRUD clients
        CommandeController.php  # Gestion des commandes + statuts
        StaffController.php     # Comptes back-office (gérants & opérateurs)
    Middleware/
      SetLocale.php                  # Applique la langue (fr/en/ar) à chaque requête
      EnsureUserIsAdmin.php          # Réserve une route au rôle « admin » (alias `admin`)
      EnsureCanAccessBackOffice.php  # Autorise « admin » OU « operator » (alias `staff`)
    Requests/                   # Form Requests (validation)
  Models/                       # Plat, Categorie, Client, Commande, CommandePlat, User
  Support/
    Panier.php                  # Service panier (session)
bootstrap/app.php               # Middlewares (web + alias admin/staff) + redirections auth
config/locales.php              # Langues prises en charge + langue par défaut
database/
  migrations/                   # Schéma métier
  seeders/                      # Données de démonstration idempotentes (carte + commandes)
lang/
  en.json · ar.json             # Traductions (clé = chaîne française)
  fr/validation.php · ar/validation.php  # Messages de validation localisés
resources/
  views/
    layouts/                    # app (front) · admin · auth (connexion)
    components/                 # icon, lang-switcher
    admin/auth/login.blade.php  # Écran de connexion gérant
    …                           # Autres vues front + admin
  css/app.css · js/app.js       # Assets compilés par Vite
routes/web.php                  # Toutes les routes HTTP
scripts/i18n_extract.php        # Extraction des clés de traduction __()
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
| statut | enum | `en_attente` (défaut) · `en_preparation` · `en_livraison` · `livree` |
| timestamps | | |

#### `details_statuses` (historique des statuts)
| Colonne | Type | Notes |
|---------|------|-------|
| id | bigint (PK) | |
| commande_id | FK → commandes | `cascadeOnDelete` |
| statut | enum | Statut atteint lors de la transition (jamais `en_attente`) |
| date_action | dateTime | Date/heure du changement de statut |
| timestamps | | |

> Le statut initial `en_attente` n'est **pas** historisé ici : son horodatage est le
> `created_at` de la commande. La table ne contient que les transitions suivantes.
>
> Lors d'un **retour en arrière** (le gérant remet un statut antérieur), les entrées de
> l'étape cible et de **tous les statuts postérieurs** sont supprimées puis l'étape cible
> est ré-enregistrée : la frise de suivi réaffiche les étapes suivantes comme « à venir ».

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

#### `users` (comptes gérant — utilisés pour l'authentification)
| Colonne | Type | Notes |
|---------|------|-------|
| id | bigint (PK) | |
| nom | string | |
| prenom | string (nullable) | |
| email | string (unique) | Identifiant de connexion |
| telephone | string (nullable) | |
| password | string (hashed) | |
| role | string | `client` · `admin` · `operator` — `admin` et `operator` accèdent au back-office (colonne convertie d'`enum` en chaîne par migration pour accueillir `operator`) |
| actif | boolean | Défaut `true` ; un compte désactivé ne peut plus se connecter |

> **Rôles du back-office.** `admin` (gérant) a accès à tout le back-office. `operator`
> (opérateur) n'accède qu'à la **page des commandes** : il voit le statut courant et ne
> peut que faire **avancer la commande d'un cran** (cf. §2.6 et §2.8).

#### Relations Eloquent

- `Categorie` **hasMany** `Plat`
- `Plat` **belongsTo** `Categorie` ; **belongsToMany** `Commande` (via `commande_plat`)
- `Client` **hasMany** `Commande`
- `Commande` **belongsTo** `Client` ; **hasMany** `CommandePlat` (lignes) et `DetailStatut` (historique de statut) ; **belongsToMany** `Plat`
- `CommandePlat` **belongsTo** `Commande` et `Plat`
- `DetailStatut` **belongsTo** `Commande` (une ligne par changement de statut)

### 2.4 Cartographie des routes

#### Front-office (espace client)

| Méthode | URL | Nom | Action |
|---------|-----|-----|--------|
| GET | `/` | `menu.index` | Carte par catégories (+ recherche `?q=`) |
| GET | `/plats/{plat}` | `menu.show` | Détail d'un plat + plats similaires |
| GET | `/panier` | `panier.index` | Affiche le panier |
| POST | `/panier/{plat}` | `panier.store` | Ajoute un plat au panier (JSON si requête AJAX) |
| PATCH | `/panier/{plat}` | `panier.update` | Modifie la quantité (JSON si requête AJAX) |
| DELETE | `/panier/{plat}` | `panier.destroy` | Retire un plat |
| DELETE | `/panier` | `panier.clear` | Vide le panier |
| GET | `/commander` | `checkout.create` | Formulaire de commande |
| POST | `/commander` | `checkout.store` | Enregistre la commande |
| GET | `/suivi` | `suivi.index` | Formulaire de recherche de commande |
| POST | `/suivi` | `suivi.search` | Recherche (throttle 10/min) |
| GET | `/suivi/{commande}` | `suivi.show` | Statut détaillé (accès contrôlé) |
| GET | `/locale/{locale}` | `locale.switch` | Change la langue (fr/en/ar), mémorisée en session |

#### Authentification gérant

| Méthode | URL | Nom | Action |
|---------|-----|-----|--------|
| GET | `/admin/login` | `admin.login` | Écran de connexion (visiteurs uniquement) |
| POST | `/admin/login` | `admin.login.attempt` | Authentification (**throttle 6/min**) |
| POST | `/admin/logout` | `admin.logout` | Déconnexion → retour à l'accueil |

#### Commandes (préfixe `/admin`, protégé par `auth` + `staff` — gérant **ou** opérateur)

| Méthode | URL | Nom | Action |
|---------|-----|-----|--------|
| GET | `/admin/commandes` | `admin.commandes.index` | Liste chronologique (filtre statut) |
| GET | `/admin/commandes/{commande}` | `admin.commandes.show` | Détail d'une commande |
| PATCH | `/admin/commandes/{commande}/statut` | `admin.commandes.statut` | Change le statut (gérant : recul libre / avance d'un cran ; opérateur : statut suivant uniquement) |

#### Back-office (préfixe `/admin`, protégé par `auth` + `admin` — gérant uniquement)

| Méthode | URL | Nom | Action |
|---------|-----|-----|--------|
| GET | `/admin` | `admin.dashboard` | Tableau de bord |
| GET/POST/… | `/admin/plats` | `admin.plats.*` | CRUD carte (sauf `show`) |
| GET/POST/… | `/admin/categories` | `admin.categories.*` | CRUD catégories (sauf `show`) |
| GET/POST/… | `/admin/clients` | `admin.clients.*` | CRUD clients (sauf `show`) |
| GET/POST/… | `/admin/equipe` | `admin.equipe.*` | Comptes back-office (gérants & opérateurs) — liste/création/édition |
| PATCH | `/admin/equipe/{user}/statut` | `admin.equipe.statut` | Activer / désactiver un membre |

> Tout le groupe `/admin` (hors `login`) est désormais protégé : un visiteur non
> authentifié est redirigé vers `/admin/login`. Les commandes sont accessibles aux
> gérants **et** aux opérateurs (`staff`) ; le reste reste réservé aux gérants (`admin`).
> Un opérateur qui tente d'ouvrir une autre page du back-office reçoit une erreur **403**.

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
   modifier les quantités, retirer un plat ou vider le panier. Le total est recalculé à
   partir du prix actuel des plats. **Ajout et réglage des quantités en AJAX** : depuis
   le menu, le bouton « Ajouter » envoie une requête en arrière-plan (`PanierController`
   répond en JSON) et met à jour le **compteur du panier** sans recharger ; sur `/panier`,
   les boutons − / + recalculent en direct le **sous-total de la ligne**, le **total** et
   le compteur. Un repli sur la soumission classique du formulaire est conservé si
   JavaScript est indisponible (les routes `panier.store` / `panier.update` renvoient
   alors une redirection avec message flash).

4. **Passage de commande** — Le formulaire demande l'identité du client, l'adresse
   de livraison et les coordonnées du destinataire. À la validation :
   - Le panier est revérifié (non vide, plats toujours disponibles).
   - Un `Client` est créé ou retrouvé par son **numéro de téléphone** (`firstOrCreate`).
   - La commande et ses lignes sont enregistrées dans une **transaction**.
   - La commande démarre au statut **« En attente »**. Cet état n'est **pas** stocké
     dans `details_statuses` : son horodatage est le `created_at` de la commande. Seules
     les transitions ultérieures alimentent l'historique de statut.
   - Le client est redirigé vers le suivi de sa commande.

5. **Suivi de commande** — Le client recherche sa commande via le **numéro** +
   le **téléphone du destinataire**. L'accès au détail est protégé : seules les
   commandes « autorisées » dans la session (après paiement ou recherche réussie)
   sont consultables ; toute autre tentative renvoie une erreur **403**. La frise de
   suivi **horodate chaque étape franchie** : « en attente » via le `created_at` de la
   commande, les statuts suivants via l'historique (`details_statuses`).

**Navigation et accès gérant.** La barre de navigation supérieure adapte ses actions
à l'état de connexion :
- **Visiteur** → bouton **Connexion** (vers l'écran de connexion gérant) ; le sélecteur
  de langue, le bascule de thème et le panier restent visibles.
- **Gérant connecté** → boutons **Espace gérant** (vers le tableau de bord) et
  **Déconnexion**. Le lien public « Espace gérant » a été retiré de la navigation des
  visiteurs.

### 2.6 Back-office (espace gérant)

> L'accès nécessite une connexion. Le **gérant** (`admin`) accède à tout ce qui suit ;
> l'**opérateur** (`operator`) n'accède qu'à la **gestion des commandes** (voir §2.8).
> La barre latérale n'affiche à l'opérateur que l'entrée « Commandes ».

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
  en temps réel (En attente → En préparation → En cours de livraison → Livrée).
  Chaque changement est **journalisé** dans l'historique de statut (`details_statuses`).
  Selon le rôle :
  - **Gérant** — un sélecteur propose les statuts **déjà atteints + le tout premier
    suivant** : il peut donc revenir à n'importe quelle étape antérieure ou n'avancer
    que d'un cran. Un retour en arrière **purge l'historique** des statuts postérieurs.
  - **Opérateur** — pas de sélecteur : il voit le **statut courant** et un unique bouton
    « Marquer … » qui fait passer la commande au **statut immédiatement suivant** (rien
    lorsque la commande est livrée). La restriction est aussi appliquée côté serveur.

- **Gestion de l'équipe** (`StaffController`, *Système → Équipe*, **gérant uniquement**) —
  Création et modification des comptes du back-office (gérants **et** opérateurs) via un
  sélecteur de **rôle**, et **activation / désactivation**. Un compte désactivé ne peut
  plus se connecter. Des garde-fous empêchent de désactiver ou de rétrograder son propre
  compte, ainsi que le **dernier gérant actif**. Les mots de passe sont hachés ; `role`
  est affecté explicitement (hors `$fillable`).

La barre supérieure et la barre latérale affichent le **membre connecté** (nom +
initiales + rôle) et un bouton de **déconnexion**. Le badge de la barre latérale et la
pastille de la cloche comptent les commandes **en attente**.

### 2.7 Règles métier importantes

- **Prix figés** : à la commande, le `prix_unitaire` est copié dans la ligne de
  commande. Une modification ultérieure du prix d'un plat n'altère pas les commandes
  passées.
- **Disponibilité** : un plat est considéré **épuisé** lorsqu'il est marqué
  indisponible (`Plat::estEpuise()`). Le gérant bascule la disponibilité depuis la
  fiche du plat.
- **Client unique par téléphone** : le `firstOrCreate` évite les doublons clients.
- **Intégrité de l'historique** : impossible de supprimer un plat déjà commandé ou
  une catégorie non vide.
- **Statut protégé** : la colonne `statut` (commande) et `role` (utilisateur) sont
  volontairement exclues du `$fillable` et affectées explicitement (anti
  mass-assignment).
- **Transitions de statut encadrées** : l'opérateur ne peut qu'avancer au **statut
  suivant** ; le gérant peut revenir à n'importe quelle étape antérieure mais ne peut
  **avancer que d'un cran**. Tout saut en avant de plus d'un statut est refusé (**403**).
- **Historique cohérent** : un retour à un statut antérieur supprime de
  `details_statuses` les entrées des statuts postérieurs (la commande redevient « neuve »
  à cette étape) ; une avancée ne touche pas aux entrées existantes.
- **Seeders idempotents** : la carte et les clients de démonstration sont créés via
  `updateOrCreate` / `firstOrCreate` ; relancer `db:seed` ne crée **aucun doublon**.

### 2.8 Authentification et contrôle d'accès

Le back-office est protégé par l'authentification standard de Laravel, renforcée par
un contrôle de rôle.

- **Rôles** — `admin` (gérant, accès complet) et `operator` (opérateur, **commandes
  uniquement**). `client` n'a aucun accès au back-office.
- **Routes protégées** — Les commandes sont encapsulées dans `auth` **+** `staff` ; tout
  le reste de `/admin/*` (hors connexion) dans `auth` **+** `admin`.
- **Middlewares de rôle** :
  - `EnsureUserIsAdmin` (alias `admin`) — renvoie **403** si l'utilisateur n'est pas un
    gérant (`isAdmin()`).
  - `EnsureCanAccessBackOffice` (alias `staff`) — autorise un gérant **ou** un opérateur
    actif (`peutAccederBackOffice()`) ; un opérateur n'atteint donc que les commandes,
    le reste du back-office lui renvoyant **403**. Un compte désactivé en cours de session
    est déconnecté proprement.
- **Contrôleur** — `Admin\AuthController` gère l'affichage du formulaire, la connexion
  et la déconnexion.
- **Écran de connexion** (`admin/login`) — formulaire e-mail + mot de passe +
  « se souvenir de moi », avec bascule de thème et sélecteur de langue ; entièrement
  traduit (FR / EN / AR).
- **Connexion** — `Auth::attempt`, puis **régénération de session** ; le **rôle est
  vérifié** : un compte `client` est immédiatement déconnecté avec le message « Ce compte
  n'a pas accès à l'espace gérant ». Après connexion, chaque rôle est dirigé vers sa page
  d'accueil — le gérant vers le **tableau de bord**, l'opérateur vers les **commandes**.
- **Limitation de débit** — `throttle:6,1` sur la tentative de connexion (anti
  force-brute).
- **Déconnexion** — ferme la session, régénère le jeton CSRF et redirige vers
  l'accueil public. Disponible depuis la barre admin **et** depuis la navigation
  front-office (lorsqu'un membre est connecté).
- **Redirections** (configurées dans `bootstrap/app.php`) — un visiteur non authentifié
  sur `/admin` est renvoyé vers `/admin/login` ; un membre déjà connecté qui ouvre
  `/admin/login` est renvoyé vers sa page d'accueil (tableau de bord ou commandes).

**Comptes de démonstration** : gérant `admin@riad.test` / `password` et opérateur
`operator@riad.test` / `password`. Les comptes sont provisionnés via le seeder/la base
(pas d'inscription publique ; `role` protégé du mass-assignment).

### 2.9 Internationalisation (FR / EN / AR)

L'interface est disponible en **français** (langue par défaut et source), **anglais**
et **arabe** (avec mise en page de droite à gauche).

- **Approche par clés françaises** — Les chaînes de l'interface sont enveloppées dans
  `__('…')` avec le texte **français comme clé**. Le français ne nécessite donc aucun
  fichier (repli naturel) ; seuls `lang/en.json` et `lang/ar.json` (≈ 250 clés chacun)
  sont maintenus.
- **Configuration** — `config/locales.php` liste les langues prises en charge (code,
  libellé natif, sens d'écriture `ltr`/`rtl`) et définit `fr` par défaut.
- **Middleware `SetLocale`** — applique à chaque requête la langue choisie (mémorisée
  en session) et **synchronise Carbon** pour des dates localisées.
- **Changement de langue** — route `GET /locale/{locale}` + composant
  `<x-lang-switcher>` (sélecteur **FR · EN · AR**) présent dans la navigation
  front-office, la barre admin et l'écran de connexion.
- **Support RTL** — `dir="rtl"` sur `<html>` en arabe, avec ajustements CSS dédiés
  (badges, interrupteur de disponibilité, frise de suivi, alignements de tableaux,
  chevrons du fil d'ariane).
- **Validation localisée** — messages de validation traduits dans `lang/fr/validation.php`
  et `lang/ar/validation.php` (l'anglais provient du framework).
- **Outillage** — `scripts/i18n_extract.php` extrait toutes les clés `__()` du code
  pour garder les fichiers de traduction synchronisés.

> Les **données de contenu** (noms et descriptions des plats, catégories) restent
> stockées dans leur langue d'origine (français) ; seule l'**interface** est traduite.

### 2.10 Sécurité

Mesures effectivement présentes dans le code :

- **Authentification du back-office** — routes `/admin` derrière `auth` + un middleware
  de rôle : `admin` (gérant) pour tout le back-office, `staff` (gérant ou opérateur) pour
  les seules commandes (403 sinon) ; voir §2.8.
- **Transitions de statut côté serveur** — l'avancée est bornée (opérateur : statut
  suivant ; gérant : +1 cran maximum), indépendamment de ce qu'affiche le formulaire.
- **Limitation de débit à la connexion** — `throttle:6,1` sur `admin.login.attempt`.
- **Régénération de session** à la connexion (prévention de la fixation de session).
- **Protection CSRF** sur tous les formulaires (`@csrf`).
- **Anti mass-assignment** : `statut` et `role` hors `$fillable`, affectés
  explicitement.
- **Validation systématique** via Form Requests et `validate()` (types, longueurs,
  existence des clés étrangères, format email, URL d'image…), avec messages localisés.
- **Upload d'image sécurisé** : l'extension est déduite du **type MIME réel** vérifié
  côté serveur (jamais du nom de fichier fourni par le client) ; seuls
  jpeg/png/webp/gif sont acceptés (≤ 4 Mo).
- **Contrôle d'accès au suivi** : une commande n'est consultable que si elle a été
  « autorisée » en session ; sinon **403**.
- **Limitation de débit** (`throttle:10,1`) sur la recherche de commande pour limiter
  l'énumération.
- **Hachage des mots de passe** (`password` casté en `hashed`).

### 2.11 Interface et design

- **Identité visuelle** : marque « Riad Saveurs », palette de couleurs chaudes
  marocaines (terracotta, safran, ocre), typographie Manrope.
- **Multilingue** : sélecteur de langue **FR · EN · AR** dans la navigation, mise en
  page RTL automatique pour l'arabe.
- **Thème clair / sombre** : bascule avec mémorisation dans `localStorage` et
  application avant rendu pour éviter le flash.
- **Composant d'icônes** : `<x-icon>` — bibliothèque SVG inline maison (plus, minus,
  cart, clock, logout…).
- **Responsive** : navigation supérieure avec compteur de panier dynamique et boutons
  d'authentification adaptatifs.
- **Interactions AJAX** (vanilla JS, `resources/js/app.js`) : ajout au panier et réglage
  des quantités sans rechargement, mise à jour en direct du compteur/sous-totaux/total et
  toasts de confirmation ; chaque action conserve un repli par formulaire classique.
- **Layouts séparés** : `layouts/app.blade.php` (front), `layouts/admin.blade.php`
  (back-office avec barre latérale) et `layouts/auth.blade.php` (écran de connexion).

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

# 5. (Optionnel) Données de démonstration — seeders idempotents
php artisan db:seed         # peut être relancé sans créer de doublon
```

### Lancement en développement

```bash
composer dev      # serveur + queue + logs + Vite (tout-en-un)
# — ou séparément —
php artisan serve # http://127.0.0.1:8000
npm run dev       # Vite (HMR) sur http://localhost:5173
```

### Accès et compte de démonstration (après `db:seed`)
- **Site client** : `http://127.0.0.1:8000/`
- **Connexion back-office** : `http://127.0.0.1:8000/admin/login`
- **Identifiants gérant** : `admin@riad.test` / `password`
- **Identifiants opérateur** : `operator@riad.test` / `password` (accès aux commandes uniquement)
- **Langue** : changer via le sélecteur **FR · EN · AR** dans la navigation.
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
cahier des charges, complété par la sécurisation du back-office et la traduction de
l'interface :

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
| **Authentification gérant + contrôle de rôle `admin`** | ✅ Réalisé |
| **Rôle `operator` (commandes uniquement, avance au statut suivant)** | ✅ Réalisé |
| **Flux de statut encadré (avance d'un cran, recul purgeant l'historique)** | ✅ Réalisé |
| **Écran de connexion + boutons Connexion/Déconnexion** | ✅ Réalisé |
| **Internationalisation FR / EN / AR (+ RTL)** | ✅ Réalisé |
| **Gestion de l'équipe (gérants & opérateurs : création/édition, activation/désactivation)** | ✅ Réalisé |
| **Seeders idempotents (aucun doublon de la carte)** | ✅ Réalisé |
| Données de démonstration (carte + commandes) | ✅ Réalisé |
| Thème clair/sombre, design responsive | ✅ Réalisé |

### 4.2 Choix techniques et justifications

- **Panier en session plutôt qu'en base** — Le client n'a pas besoin de compte ; le
  panier est éphémère et propre à la session, ce qui simplifie le parcours et évite
  une table supplémentaire.

- **`commande_plat` comme vraie table de lignes** (avec clé primaire et timestamps),
  et non simple pivot — Permet de **figer le prix unitaire** au moment de la commande,
  garantissant un historique fidèle même si la carte évolue.

- **Transaction au checkout** — La création de la commande et de ses lignes se fait
  de façon **atomique** : aucune commande partielle en cas d'erreur.

- **Authentification garde `web` + middleware de rôle dédié** — On s'appuie sur la
  garde Laravel standard, et un middleware `admin` séparé applique le contrôle de rôle.
  Le rôle est **aussi vérifié au moment de la connexion** (un client est refusé), pour
  une défense en profondeur.

- **i18n par clés françaises** — Le texte français sert de clé de traduction : diff
  minimal sur les vues, et le français reste le repli naturel sans fichier dédié.

- **Seeders idempotents** (`updateOrCreate` / `firstOrCreate`) — Relancer `db:seed`
  met à jour les données existantes au lieu de les dupliquer.

- **`firstOrCreate` du client par téléphone** — Évite la prolifération de doublons
  clients tout en gardant un parcours sans inscription.

- **Suppression défensive** — Un plat commandé ou une catégorie non vide ne peuvent
  être supprimés, pour préserver l'intégrité référentielle et l'historique.

- **SQLite par défaut** — Démarrage immédiat sans serveur de base de données ;
  migration vers MySQL possible par simple changement de configuration.

### 4.3 Mesures de sécurité mises en place

Récapitulatif des protections implémentées (détaillées aux §2.8 et §2.10) :

1. **Authentification du back-office** (`auth`) + **contrôle de rôle `admin`** (403 sinon).
2. **Vérification du rôle à la connexion** (un compte client est refusé).
3. **Limitation de débit à la connexion** (`throttle:6,1`) + **régénération de session**.
4. Protection CSRF sur tous les formulaires.
5. Protection contre le mass-assignment des colonnes sensibles (`statut`, `role`).
6. Validation stricte des entrées (Form Requests + règles), messages localisés.
7. Upload d'image validé par type MIME réel, taille et format restreints.
8. Contrôle d'accès au suivi de commande via autorisation en session (403 sinon).
9. Limitation de débit sur la recherche de commande.
10. Hachage automatique des mots de passe.

### 4.4 Points d'attention et limites connues

- **Pas de gestion de paiement en ligne** — La commande est enregistrée sans
  encaissement (paiement supposé à la livraison).

- **Pas d'espace client authentifié** — Seul le gérant dispose d'un compte ; côté
  client, le suivi repose sur numéro + téléphone, sans historique de compte.

- **Pas d'inscription gérant publique** — Aucune inscription ouverte : les nouveaux
  comptes gérant sont créés depuis le back-office (*Système → Équipe*) par un gérant
  existant (`role` protégé du mass-assignment).

- **Vulnérabilités npm** — `npm audit` signale des vulnérabilités dans la chaîne
  d'outils de développement (Vite). Sans impact sur la production (dépendances de
  build), mais à surveiller.

### 4.5 Améliorations futures recommandées

| Priorité | Amélioration |
|----------|--------------|
| 🟠 Moyenne | Notifications client (email/SMS) aux changements de statut. |
| 🟡 Basse | Paiement en ligne. |
| 🟡 Basse | Espace client authentifié avec historique de commandes. |
| 🟡 Basse | Traduction du contenu de la carte (plats/catégories) au niveau base de données. |
| 🟡 Basse | Suite de tests automatisés (front + back) plus étoffée. |
| 🟡 Basse | Mise à jour des dépendances npm signalées par `npm audit`. |

### 4.6 Synthèse

Riad Saveurs est une application Laravel 12 **fonctionnellement complète, sécurisée et
multilingue**, couvrant l'ensemble du parcours client (menu → panier → commande →
suivi) et de la gestion gérant (carte, catégories, clients, commandes, tableau de
bord). Le code applique de bonnes pratiques (transactions, prix figés, protection
mass-assignment, validation, upload sécurisé, suppression défensive, seeders
idempotents).

Le **point bloquant identifié précédemment — l'absence d'authentification sur le
back-office — est désormais résolu** : les routes `/admin` sont protégées par `auth`
et un contrôle de rôle `admin`, avec un écran de connexion dédié. L'interface est par
ailleurs disponible en **français, anglais et arabe (RTL)**. L'application est prête à
être exploitée ; les évolutions restantes (paiement en ligne, espace client, gestion
des comptes du personnel) relèvent du confort fonctionnel et non d'un prérequis de
mise en production.

---

*Document généré le 13 juin 2026, mis à jour le 15 juin 2026 — Riad Saveurs.*
