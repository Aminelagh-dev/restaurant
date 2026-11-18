---
marp: true
title: Riad Saveurs — Présentation du projet
theme: default
paginate: true
---

<!--
🎙️ TEXTE RACONTÉ (notes du présentateur — non affichées à l'écran)
Bonjour à tous. Je vais vous présenter Riad Saveurs, une plateforme web de
commande en ligne dédiée à la cuisine marocaine traditionnelle, développée avec
le framework Laravel 12. Le projet propose une expérience client fluide ainsi
qu'un espace de gestion sécurisé pour le gérant, le tout disponible en trois
langues : français, anglais et arabe.
-->

# Riad Saveurs
## Plateforme de commande en ligne — Cuisine marocaine traditionnelle

**Application web Laravel 12** · Front-office client + Back-office gérant
Multilingue **FR · EN · AR (RTL)** · Back-office **sécurisé**

---

<!--
🎙️ TEXTE RACONTÉ
Voici le déroulé de la présentation. Je commencerai par une introduction du
produit, puis je présenterai quatre problématiques métier que nous avons
rencontrées et les solutions techniques apportées à chacune. J'enchaînerai sur
les outils utilisés, avant de conclure.
-->

## 🖥️ Texte affiché — Plan

1. **Introduction**
2. **Problématique et solution proposée** (4 problématiques · 4 solutions)
3. **Outils utilisés**
4. **Conclusion**

---

<!--
🎙️ TEXTE RACONTÉ
Riad Saveurs met en valeur la cuisine marocaine et permet de commander des plats
comme la Harira, les tagines ou le couscous en quelques étapes seulement, sans
avoir besoin de créer un compte. La plateforme est organisée en deux espaces
complémentaires : un espace client tourné vers la vitrine et la commande, et un
espace gérant protégé, dédié à la gestion complète du restaurant.
-->

## 🖥️ Texte affiché — 1. Introduction

Plateforme web mettant en valeur la **cuisine marocaine traditionnelle**.
Commander **sans créer de compte**, en quelques étapes.

Deux espaces complémentaires :

- 🍽️ **Espace client (front-office)** — parcourir la carte, consulter un plat,
  remplir un panier, commander et **suivre** sa commande.
- 🔐 **Espace gérant (back-office)** — tableau de bord **protégé** : carte,
  catégories, clients, commandes et indicateurs d'activité.

---

<!--
🎙️ TEXTE RACONTÉ
Le projet poursuit quatre objectifs. D'abord, offrir une expérience client sans
friction, structurée en quatre étapes. Ensuite, doter le gérant d'un outil de
gestion autonome et sécurisé, fonctionnant en temps réel. Troisièmement, rendre
le service accessible à l'international grâce au multilingue. Et enfin, valoriser
le patrimoine culinaire marocain à travers un design soigné aux couleurs chaudes.
-->

## 🖥️ Texte affiché — 1. Introduction : objectifs

- **Expérience client fluide** : menu → panier → commande → suivi, sans inscription.
- **Outil de gestion autonome et sécurisé** pour le gérant, en temps réel.
- **Accessibilité internationale** (FR / EN / AR).
- **Valorisation du patrimoine culinaire marocain** (terracotta, safran, ocre).

**Public visé** : clients amateurs de cuisine marocaine · gérant et personnel.

---

<!--
🎙️ TEXTE RACONTÉ
Passons au cœur du projet. Nous avons identifié quatre problématiques métier
majeures. Pour chacune, une solution technique concrète a été conçue et mise en
œuvre. Ce tableau les résume avant que je ne les détaille une par une.
-->

## 🖥️ Texte affiché — 2. Problématiques & solutions

| # | Problématique | Solution |
|---|---------------|----------|
| 1 | Commander sans friction | Parcours 4 étapes + panier en session (AJAX) |
| 2 | Accès admin non protégé | Authentification + rôles `admin` / `operator` |
| 3 | Intégrité des commandes | Prix figés + transaction + historique |
| 4 | Public international | Internationalisation FR / EN / AR (RTL) |

---

<!--
🎙️ TEXTE RACONTÉ — Problématique 1
La première problématique concerne le parcours d'achat. Obliger un client à
créer un compte avant de commander génère beaucoup d'abandons. Notre objectif
était donc un parcours aussi simple et rapide que possible, sans aucune barrière
à l'entrée.
-->

## 🖥️ Texte affiché — Problématique 1️⃣

### ❓ Commander sans friction

Obliger un client à **créer un compte** avant de commander provoque de
l'abandon.

Le parcours d'achat doit être **simple, rapide et sans barrière**.

---

<!--
🎙️ TEXTE RACONTÉ — Solution 1
Pour y répondre, nous avons conçu un parcours en quatre étapes : menu, panier,
commande, suivi, sans aucune inscription. Le panier est stocké en session côté
serveur. L'ajout au panier et le réglage des quantités se font en AJAX, donc
sans rechargement de page : le compteur, les sous-totaux et le total se mettent
à jour en direct. Enfin, le client est identifié simplement par son numéro de
téléphone, ce qui évite les doublons sans imposer de mot de passe.
-->

## 🖥️ Texte affiché — Solution 1️⃣

### ✅ Parcours simplifié, sans compte

- Parcours **en 4 étapes** : menu → panier → commande → suivi.
- **Panier en session** (`[plat_id => quantité]`), recalculé au prix actuel.
- **AJAX** : compteur, sous-totaux et total mis à jour **sans rechargement**
  (repli formulaire si JS absent).
- Client **identifié par téléphone** (`firstOrCreate`) — pas de doublon, pas de
  mot de passe.

---

<!--
🎙️ TEXTE RACONTÉ — Problématique 2
Deuxième problématique, et c'était un point bloquant : l'ancien accès à l'espace
d'administration était totalement libre. N'importe quel visiteur pouvait ouvrir
la page admin et gérer la carte ou les commandes. C'était un risque de sécurité
majeur qu'il fallait absolument corriger.
-->

## 🖥️ Texte affiché — Problématique 2️⃣

### ❓ Accès gérant non protégé

L'ancien accès `/admin` était **libre** : n'importe qui pouvait gérer la carte
et les commandes.

➡️ **Risque de sécurité majeur.**

---

<!--
🎙️ TEXTE RACONTÉ — Solution 2
La solution repose sur l'authentification standard de Laravel, avec un écran de
connexion dédié. Nous avons introduit deux rôles : admin, qui a accès à tout, et
operator, limité à la seule gestion des commandes. Des middlewares de rôle
renvoient une erreur 403 en cas d'accès non autorisé. Enfin, plusieurs mesures
de durcissement ont été ajoutées : limitation des tentatives de connexion,
régénération de session, vérification du rôle à la connexion, et protection
contre le mass-assignment.
-->

## 🖥️ Texte affiché — Solution 2️⃣

### ✅ Authentification + contrôle de rôle

- **Authentification Laravel** (garde `web`) + **écran de connexion** dédié.
- **Deux rôles** : `admin` (accès complet) · `operator` (commandes uniquement).
- **Middlewares de rôle** → erreur **403** si non autorisé.
- **Durcissement** : `throttle:6,1`, **régénération de session**, vérification du
  rôle (un `client` est refusé), `role` hors `$fillable`.

---

<!--
🎙️ TEXTE RACONTÉ — Problématique 3
Troisième problématique : l'intégrité des données de commande. Si le gérant
modifie le prix d'un plat, les commandes déjà passées ne doivent surtout pas en
être affectées. Et une commande ne doit jamais être enregistrée à moitié, par
exemple en cas d'erreur en cours d'écriture.
-->

## 🖥️ Texte affiché — Problématique 3️⃣

### ❓ Intégrité des commandes

- Un changement de **prix** ne doit pas altérer les **commandes passées**.
- Une commande ne doit **jamais** être enregistrée **à moitié**.

---

<!--
🎙️ TEXTE RACONTÉ — Solution 3
Nous figeons le prix unitaire dans la ligne de commande au moment de l'achat :
la table commande_plat est une véritable table de lignes, pas un simple pivot.
La création de la commande et de ses lignes se fait dans une transaction
atomique : c'est tout ou rien. Chaque changement de statut est journalisé dans
un historique. Et nous appliquons une suppression défensive : impossible de
supprimer un plat déjà commandé ou une catégorie non vide.
-->

## 🖥️ Texte affiché — Solution 3️⃣

### ✅ Prix figés, transaction, historique

- **Prix figés** : `prix_unitaire` copié dans la ligne au moment de l'achat.
- **Transaction atomique** au checkout : commande + lignes, ou rien.
- **Historique des statuts** (`details_statuses`) journalisant chaque transition.
- **Suppression défensive** : pas de suppression d'un plat commandé ou d'une
  catégorie non vide.

---

<!--
🎙️ TEXTE RACONTÉ — Problématique 4
Dernière problématique : le restaurant vise une clientèle variée. L'interface
devait donc être disponible en plusieurs langues, et notamment en arabe, qui
s'écrit de droite à gauche, ce qui impose des contraintes d'affichage
spécifiques.
-->

## 🖥️ Texte affiché — Problématique 4️⃣

### ❓ Servir un public international

L'interface doit être disponible en **plusieurs langues**, dont l'**arabe écrit
de droite à gauche** (RTL).

---

<!--
🎙️ TEXTE RACONTÉ — Solution 4
L'interface est traduite en français, anglais et arabe via le système de
traduction de Laravel, en utilisant le texte français comme clé, ce qui réduit
le travail de maintenance. Le support droite-à-gauche est complet pour l'arabe.
Un middleware applique la langue choisie à chaque requête et localise même les
dates. Un sélecteur de langue est présent partout, et les messages de validation
sont eux aussi traduits.
-->

## 🖥️ Texte affiché — Solution 4️⃣

### ✅ Internationalisation FR / EN / AR (RTL)

- **Traductions JSON Laravel** (`__()`) avec le **français comme clé**.
- **Support RTL** complet pour l'arabe (`dir="rtl"` + ajustements CSS).
- **Middleware `SetLocale`** : langue en session + dates localisées (Carbon).
- **Sélecteur FR · EN · AR** partout · **validation localisée**.

---

<!--
🎙️ TEXTE RACONTÉ
Voyons maintenant les outils utilisés. Le backend repose sur PHP 8.2 et Laravel
12 avec l'ORM Eloquent. Le frontend utilise Blade, Vite 7 et Tailwind CSS 4,
avec un peu de JavaScript vanilla pour les interactions AJAX. La base de données
est SQLite par défaut, mais peut basculer vers MySQL. L'authentification, le
multilingue et les tests s'appuient sur les briques standard de Laravel et
PHPUnit.
-->

## 🖥️ Texte affiché — 3. Outils utilisés

| Couche | Technologie |
|--------|-------------|
| **Backend** | PHP 8.2+ · **Laravel 12** · Eloquent ORM |
| **Frontend** | Blade · **Vite 7** · **Tailwind CSS 4** · JS vanilla (AJAX) |
| **Base de données** | **SQLite** (défaut) / MySQL |
| **Authentification** | Garde `web` Laravel + middlewares de rôle |
| **Internationalisation** | Traductions JSON `__()` + middleware de locale |
| **Tests / Dev** | PHPUnit 11 · Pail · Pint · Sail |

---

<!--
🎙️ TEXTE RACONTÉ
Quelques mots sur la justification de ces choix. Laravel nous apporte un cadre
MVC robuste avec sécurité intégrée. SQLite permet un démarrage immédiat sans
serveur de base de données. Tailwind et Vite offrent un design rapide, cohérent
et responsive. Le panier en session évite une table superflue. Et notre approche
d'internationalisation par clés françaises minimise les fichiers à maintenir.
-->

## 🖥️ Texte affiché — 3. Pourquoi ces choix ?

- **Laravel 12 (MVC)** — robuste, sécurité intégrée (CSRF, validation, hachage).
- **SQLite par défaut** — démarrage immédiat ; bascule MySQL par configuration.
- **Tailwind 4 + Vite 7** — design rapide, cohérent et responsive.
- **Panier en session** — éphémère, aucune table superflue.
- **i18n par clés françaises** — repli naturel, fichiers minimaux.

---

<!--
🎙️ TEXTE RACONTÉ
Pour conclure, Riad Saveurs est une application fonctionnellement complète,
sécurisée et multilingue. Elle couvre tout le parcours client et toute la
gestion gérant, en appliquant de bonnes pratiques de développement. Surtout, le
point bloquant identifié au départ — l'absence de protection du back-office — est
désormais résolu.
-->

## 🖥️ Texte affiché — 4. Conclusion

Application Laravel 12 **complète, sécurisée et multilingue** :

- ✅ Parcours client : **menu → panier → commande → suivi**
- ✅ Gestion gérant : carte, catégories, clients, commandes, tableau de bord
- ✅ Bonnes pratiques : transactions, prix figés, anti mass-assignment,
  validation, upload sécurisé, seeders idempotents

➡️ Le **point bloquant** (back-office non protégé) est **résolu**.

---

<!--
🎙️ TEXTE RACONTÉ
L'application est prête à être exploitée. Les évolutions qui restent relèvent du
confort fonctionnel et non d'un prérequis de mise en production : notifications
au client, paiement en ligne, espace client authentifié, ou traduction du
contenu de la carte. Je vous remercie de votre attention et je suis à votre
disposition pour vos questions.
-->

## 🖥️ Texte affiché — 4. Perspectives

| Priorité | Amélioration |
|----------|--------------|
| 🟠 Moyenne | Notifications client (email/SMS) au changement de statut |
| 🟡 Basse | Paiement en ligne |
| 🟡 Basse | Espace client authentifié avec historique |
| 🟡 Basse | Traduction du contenu de la carte (BDD) |

**Merci de votre attention — Questions ?**
