# Riad Saveurs — Complete Documentation

> **Online Ordering Platform — Traditional Moroccan Cuisine**
> A Laravel 12 web application that lets customers discover and order Moroccan
> dishes (Harira, Tagines, Couscous, Pastilla, Rfissa…), with a full back-office
> for the restaurant manager.

| | |
|---|---|
| **Project name** | Riad Saveurs |
| **Type** | Food-ordering web application (front-office + back-office) |
| **Stack version** | Laravel 12 · PHP 8.2+ · Vite 7 · Tailwind CSS 4 |
| **Database** | SQLite (default) or MySQL |
| **Interface language** | French |
| **Document date** | June 13, 2026 |

---

## Table of contents

1. [Presentation](#1-presentation)
2. [All information about the website](#2-all-information-about-the-website)
   - 2.1 [Technical stack](#21-technical-stack)
   - 2.2 [Overall architecture](#22-overall-architecture)
   - 2.3 [Data model](#23-data-model)
   - 2.4 [Route map](#24-route-map)
   - 2.5 [Front-office (customer area)](#25-front-office-customer-area)
   - 2.6 [Back-office (manager area)](#26-back-office-manager-area)
   - 2.7 [Key business rules](#27-key-business-rules)
   - 2.8 [Security](#28-security)
   - 2.9 [Interface and design](#29-interface-and-design)
3. [Installation and operation](#3-installation-and-operation)
4. [Technical report](#4-technical-report)
   - 4.1 [Work delivered](#41-work-delivered)
   - 4.2 [Technical choices and rationale](#42-technical-choices-and-rationale)
   - 4.3 [Security measures implemented](#43-security-measures-implemented)
   - 4.4 [Points of attention and known limitations](#44-points-of-attention-and-known-limitations)
   - 4.5 [Recommended future improvements](#45-recommended-future-improvements)
   - 4.6 [Summary](#46-summary)

---

## 1. Presentation

**Riad Saveurs** is a web platform that showcases traditional Moroccan cuisine and
lets a customer place an order in a few steps, with no account required.

The site is made up of two areas:

- **Customer area (front-office)** — an elegant gastronomic storefront where the
  visitor browses the menu by category, views a dish's details (ingredients,
  preparation time, price), fills a cart, places an order with a delivery address,
  and then tracks its progress.

- **Manager area (back-office)** — an administration dashboard where the manager
  handles the menu (dishes, prices, out-of-stock), categories, customers and
  orders, and reviews activity indicators (revenue, best-selling dishes, order
  status breakdown).

### Product goals

- Deliver a **smooth customer experience**: no mandatory sign-up, a 4-step journey
  (menu → cart → order → tracking).
- Give the manager a **self-service tool** to run the whole menu and orders in real
  time.
- Highlight **Moroccan culinary heritage** through polished visuals and warm colors
  (terracotta, saffron, ochre).

### Target audience

- **Customers**: lovers of Moroccan cuisine wishing to order for delivery.
- **Manager / restaurant staff**: menu management and order follow-up.

---

## 2. All information about the website

### 2.1 Technical stack

| Layer | Technology | Role |
|-------|------------|------|
| Backend | **PHP 8.2+**, **Laravel 12**, Eloquent ORM | Business logic, routing, persistence |
| Frontend | **Blade**, **Vite 7**, **Tailwind CSS 4** | View rendering, asset bundling |
| Database | **SQLite** (default) / MySQL | Data storage |
| Sessions / Cart | `database` driver | Cart and access grants stored in session |
| Tests | **PHPUnit 11** | Automated tests |
| Dev tooling | Laravel Pail (logs), Pint (formatting), Sail | Developer comfort |

### 2.2 Overall architecture

The application follows Laravel's standard **MVC** architecture, with a clean
separation between front-office and back-office.

```
app/
  Http/
    Controllers/
      MenuController.php        # Menu + dish detail (customer)
      PanierController.php      # Session cart (customer)
      CheckoutController.php    # Order placement (customer)
      SuiviController.php       # Order tracking (customer)
      Admin/
        DashboardController.php # Dashboard (manager)
        PlatsController.php     # Menu CRUD
        CategorieController.php # Category CRUD
        ClientController.php    # Customer CRUD
        CommandeController.php  # Order management + statuses
    Requests/                   # Form Requests (validation)
      CheckoutRequest.php
      StorePlatRequest.php
      UpdatePlatRequest.php
  Models/                       # Plats, Categorie, Client, Commande, CommandePlat, User
  Support/
    Panier.php                  # Cart service (session)
database/
  migrations/                   # Business schema
  seeders/                      # Demo data (menu + orders)
resources/
  views/                        # Blade views (front + admin + components)
  css/app.css · js/app.js       # Assets bundled by Vite
routes/web.php                  # All HTTP routes
public/images/plats/            # Dish photos
```

### 2.3 Data model

Six business tables structure the application.

#### `categories`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint (PK) | |
| nom | string | e.g. "Entrées", "Plats principaux" |
| description | text (nullable) | |
| timestamps | | |

#### `plats` (dishes)
| Column | Type | Notes |
|--------|------|-------|
| id | bigint (PK) | |
| categorie_id | FK → categories | `cascadeOnDelete` |
| nom | string | |
| description | text | |
| ingredients | text | Free-text list |
| temps_preparation | integer | Minutes |
| prix | decimal(10,2) | In MAD (DH) |
| image | string (nullable) | Absolute URL **or** local path |
| stock | integer | Default 0 |
| disponible | boolean | Default `true` |
| timestamps | | |

#### `clients` (customers)
| Column | Type | Notes |
|--------|------|-------|
| id | bigint (PK) | |
| nom, prenom | string | Last/first name |
| telephone | string | Used as uniqueness key at checkout |
| email | string (nullable) | |
| timestamps | | |

#### `commandes` (orders)
| Column | Type | Notes |
|--------|------|-------|
| id | bigint (PK) | Serves as the "order number" |
| client_id | FK → clients | `cascadeOnDelete` |
| date_commande | dateTime | |
| montant_total | decimal(10,2) | |
| adresse_livraison | string | Delivery address |
| nom_recepteur | string | Delivery recipient |
| telephone_recepteur | string | Used for order tracking |
| statut | enum | `en_preparation` · `en_livraison` · `livree` |
| timestamps | | |

#### `commande_plat` (order lines)
| Column | Type | Notes |
|--------|------|-------|
| id | bigint (PK) | A real line table, not a plain pivot |
| commande_id | FK → commandes | `cascadeOnDelete` |
| plat_id | FK → plats | `cascadeOnDelete` |
| quantite | integer | |
| prix_unitaire | decimal(10,2) | **Price frozen** at order time |
| sous_total | decimal(10,2) | |
| unique(commande_id, plat_id) | | A dish appears once per order |

#### `users` (manager accounts)
| Column | Type | Notes |
|--------|------|-------|
| id | bigint (PK) | |
| nom | string | |
| prenom | string (nullable) | |
| email | string (unique) | |
| telephone | string (nullable) | |
| password | string (hashed) | |
| role | enum | `client` · `admin` |

#### Eloquent relationships

- `Categorie` **hasMany** `Plats`
- `Plats` **belongsTo** `Categorie`; **belongsToMany** `Commande` (via `commande_plat`)
- `Client` **hasMany** `Commande`
- `Commande` **belongsTo** `Client`; **hasMany** `CommandePlat` (lines); **belongsToMany** `Plats`
- `CommandePlat` **belongsTo** `Commande` and `Plats`

### 2.4 Route map

#### Front-office (customer area)

| Method | URL | Name | Action |
|--------|-----|------|--------|
| GET | `/` | `menu.index` | Menu by categories (+ search `?q=`) |
| GET | `/plats/{plat}` | `menu.show` | Dish detail + similar dishes |
| GET | `/panier` | `panier.index` | Show cart |
| POST | `/panier/{plat}` | `panier.store` | Add dish to cart |
| PATCH | `/panier/{plat}` | `panier.update` | Change quantity |
| DELETE | `/panier/{plat}` | `panier.destroy` | Remove a dish |
| DELETE | `/panier` | `panier.clear` | Empty the cart |
| GET | `/commander` | `checkout.create` | Order form |
| POST | `/commander` | `checkout.store` | Save the order |
| GET | `/suivi` | `suivi.index` | Order search form |
| POST | `/suivi` | `suivi.search` | Search (throttle 10/min) |
| GET | `/suivi/{commande}` | `suivi.show` | Detailed status (access-controlled) |

#### Back-office (`/admin` prefix)

| Method | URL | Name | Action |
|--------|-----|------|--------|
| GET | `/admin` | `admin.dashboard` | Dashboard |
| GET/POST/… | `/admin/plats` | `admin.plats.*` | Menu CRUD (except `show`) |
| GET/POST/… | `/admin/categories` | `admin.categories.*` | Category CRUD (except `show`) |
| GET | `/admin/commandes` | `admin.commandes.index` | Chronological list (status filter) |
| GET | `/admin/commandes/{commande}` | `admin.commandes.show` | Order detail |
| PATCH | `/admin/commandes/{commande}/statut` | `admin.commandes.statut` | Change status |
| GET/POST/… | `/admin/clients` | `admin.clients.*` | Customer CRUD (except `show`) |

### 2.5 Front-office (customer area)

**A 4-step journey:**

1. **Gastronomic menu** — Dishes are grouped by category. A search bar filters by
   name or description. Available dishes are listed first
   (`orderByDesc('disponible')`). Empty categories are hidden.

2. **Dish detail** — Shows the description, ingredient list, preparation time, price
   and up to 3 similar dishes from the same category. A quantity stepper lets the
   customer add to the cart.

3. **Cart (session-based)** — The cart is stored server-side in the session as
   `[dish_id => quantity]` via the `App\Support\Panier` service. The customer can
   change quantities (auto-submitted), remove a dish or empty the cart. The total is
   recomputed from the dishes' current prices.

4. **Order placement** — The form asks for the customer's identity, the delivery
   address and the recipient's details. On submit:
   - The cart is re-checked (non-empty, dishes still available).
   - A `Client` is created or found by **phone number** (`firstOrCreate`).
   - The order and its lines are saved inside a **transaction**.
   - Each dish's **stock is decremented**; a dish whose stock reaches 0 is
     automatically marked unavailable.
   - The customer is redirected to their order tracking page.

5. **Order tracking** — The customer searches their order by **number** +
   **recipient phone**. Access to the detail is protected: only orders "authorized"
   in the session (after checkout or a successful search) can be viewed; any other
   attempt returns a **403** error.

### 2.6 Back-office (manager area)

- **Dashboard** (`DashboardController`) — Key indicators:
  - Number of dishes, out-of-stock dishes, categories, customers, orders.
  - **Today's revenue** and **total revenue**.
  - **Top 5 best-selling dishes** (quantity + revenue).
  - **Revenue over the last 7 days** (time series).
  - Order breakdown by status.
  - 6 most recent orders.

- **Menu management** (`PlatsController`) — Full dish CRUD with search and category
  filter, pagination (12/page). The image may be provided by **file upload** or by
  **URL**. A dish already present in orders cannot be deleted (history preservation):
  the manager is prompted to mark it "Out of stock" instead.

- **Category management** (`CategorieController`) — CRUD; a category containing
  dishes cannot be deleted.

- **Customer management** (`ClientController`) — CRUD with multi-field search (last
  name, first name, phone, email) and an order counter. Unique email.

- **Order management** (`CommandeController`) — Paginated chronological list
  (15/page), filterable by status, order detail, and **real-time status change**
  (In preparation → Out for delivery → Delivered).

### 2.7 Key business rules

- **Frozen prices**: at order time, `prix_unitaire` is copied into the order line. A
  later change to a dish's price does not alter past orders.
- **Stock management**: automatic decrement at order time; flagged "unavailable" as
  soon as stock reaches 0. A dish is considered **out of stock** if it is marked
  unavailable **or** its stock is ≤ 0 (`Plats::estEpuise()`).
- **One customer per phone**: `firstOrCreate` avoids duplicate customers.
- **History integrity**: a dish already ordered, or a non-empty category, cannot be
  deleted.
- **Protected status**: the `statut` column (order) and `role` (user) are
  deliberately excluded from `$fillable` and assigned explicitly (anti
  mass-assignment).

### 2.8 Security

Measures actually present in the code:

- **CSRF protection** on every form (`@csrf`).
- **Anti mass-assignment**: `statut` and `role` outside `$fillable`, assigned
  explicitly.
- **Systematic validation** via Form Requests and `validate()` (types, lengths,
  foreign-key existence, email format, image URL…).
- **Secure image upload**: the extension is derived from the **real MIME type**
  verified server-side (never from the client-supplied filename); only
  jpeg/png/webp/gif are accepted (≤ 4 MB).
- **Tracking access control**: an order is viewable only if it was "authorized" in
  the session; otherwise **403**.
- **Rate limiting** (`throttle:10,1`) on order search to curb enumeration.
- **Password hashing** (`password` cast to `hashed`).

> ⚠️ An important security point regarding the back-office is detailed in the
> [technical report, §4.4](#44-points-of-attention-and-known-limitations).

### 2.9 Interface and design

- **Visual identity**: "Riad Saveurs" brand, warm Moroccan color palette
  (terracotta, saffron, ochre), Manrope typography.
- **Light / dark theme**: toggle persisted in `localStorage` and applied before
  render to avoid flashing.
- **Icon component**: `<x-icon>` — an in-house inline SVG library (plus, minus,
  cart, clock…).
- **Responsive**: top navigation with a dynamic cart counter.
- **Separate layouts**: `layouts/app.blade.php` (front) and
  `layouts/admin.blade.php` (back-office with a sidebar).

---

## 3. Installation and operation

### Requirements
- PHP **8.2+** (tested on 8.3)
- Composer 2
- Node.js 18+ and npm

### Installation

```bash
# 1. PHP dependencies
composer install

# 2. Front-end dependencies + esbuild binary
npm install
npm rebuild esbuild        # if the post-install did not run

# 3. Configuration
cp .env.example .env        # if .env is missing
php artisan key:generate

# 4. Database (SQLite by default)
#    Create the database/database.sqlite file, then:
php artisan migrate

# 5. (Optional) Demo data
php artisan db:seed
```

### Running in development

```bash
composer dev      # server + queue + logs + Vite (all-in-one)
# — or separately —
php artisan serve # http://127.0.0.1:8000
npm run dev       # Vite (HMR) on http://localhost:5173
```

### Demo account (after `db:seed`)
- **Manager**: `admin@riad.test` / `password`
- Data: a full Moroccan menu (Harira, Tagines, Couscous, Pastilla, Desserts,
  Teas/Juices), 5 fictitious customers and orders spread over 7 days.

### Tests

```bash
composer test     # PHPUnit
```

---

## 4. Technical report

### 4.1 Work delivered

The delivered application covers the **entire functional scope** described in the
specification:

| Area | Status |
|------|--------|
| Gastronomic menu by category + search | ✅ Done |
| Dish detail (ingredients, time, price, similar) | ✅ Done |
| Session cart (multi-dish, quantities, total) | ✅ Done |
| Order placement (address + recipient) | ✅ Done |
| Order tracking by status | ✅ Done |
| Menu CRUD (dishes, prices, out-of-stock, images) | ✅ Done |
| Category CRUD | ✅ Done |
| Customer CRUD | ✅ Done |
| Order management + status change | ✅ Done |
| Dashboard (popular dishes, daily revenue) | ✅ Done |
| Demo data (menu + orders) | ✅ Done |
| Light/dark theme, responsive design | ✅ Done |

### 4.2 Technical choices and rationale

- **Session cart instead of a database cart** — The customer needs no account; the
  cart is ephemeral and session-scoped, which simplifies the journey and avoids an
  extra table.

- **`commande_plat` as a real line table** (with a primary key and timestamps),
  not a plain pivot — Allows the **unit price to be frozen** at order time,
  guaranteeing a faithful history even as the menu evolves.

- **Transaction at checkout** — Creating the order, its lines and decrementing stock
  happen **atomically**: no partial order on error.

- **`firstOrCreate` of the customer by phone** — Avoids duplicate customers while
  keeping a sign-up-free journey.

- **Defensive deletion** — An ordered dish or a non-empty category cannot be
  deleted, preserving referential integrity and history.

- **SQLite by default** — Instant start-up with no database server; migration to
  MySQL is a simple configuration change.

### 4.3 Security measures implemented

Summary of the protections in place (detailed in §2.8):

1. CSRF protection on every form.
2. Mass-assignment protection of sensitive columns (`statut`, `role`).
3. Strict input validation (Form Requests + rules).
4. Image upload validated by real MIME type, with size and format restrictions.
5. Order-tracking access control via session authorization (403 otherwise).
6. Rate limiting on order search.
7. Automatic password hashing.

### 4.4 Points of attention and known limitations

- **⚠️ The `/admin` back-office is not protected by authentication.** The `User`
  model and roles (`client`/`admin`) exist and a manager account is created at seed
  time, but **no `auth` middleware or role check is applied** to the `/admin` routes
  in `routes/web.php`. As it stands, any visitor can reach the dashboard and all CRUD
  operations. **This is the #1 priority to fix before any production deployment**
  (see §4.5).

- **`Plat` model naming inconsistency** — The file `app/Models/Plats.php` initially
  contained a `Plat` class (PSR-4 non-compliant, skipped by the autoloader). The
  class the application uses is `Plats`. This should be harmonized to avoid
  confusion.

- **npm vulnerabilities** — `npm audit` reports vulnerabilities in the development
  toolchain (Vite). No production impact (build dependencies), but worth monitoring.

- **No online payment** — The order is recorded without collecting payment (assumed
  cash on delivery).

- **No authenticated customer area** — Tracking relies on number + phone, with no
  account history.

### 4.5 Recommended future improvements

| Priority | Improvement |
|----------|-------------|
| 🔴 High | **Protect `/admin`** with `auth` middleware + `admin` role check, and add a manager login screen. |
| 🟠 Medium | Harmonize the `Plats`/`Plat` model naming (PSR-4). |
| 🟠 Medium | Customer notifications (email/SMS) on status changes. |
| 🟡 Low | Online payment. |
| 🟡 Low | Authenticated customer area with order history. |
| 🟡 Low | A more extensive automated test suite (front + back). |
| 🟡 Low | Update the npm dependencies flagged by `npm audit`. |

### 4.6 Summary

Riad Saveurs is a **functionally complete** and well-structured Laravel 12
application, covering the full customer journey (menu → cart → order → tracking) and
manager operations (menu, categories, customers, orders, dashboard). The code applies
good practices (transactions, frozen prices, mass-assignment protection, validation,
secure upload, defensive deletion).

The **only blocker for production** is the **lack of authentication on the
back-office**: the `/admin` routes must be protected before any deployment. Once that
point is addressed, the application is ready for operation.

---

*Document generated on June 13, 2026 — Riad Saveurs.*
