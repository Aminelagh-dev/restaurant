/* Génère PRESENTATION.pptx — Riad Saveurs
 * Style inspiré d'une soutenance (page de titre, slide PLAN en chiffres
 * romains, intercalaires de section). Texte AFFICHÉ sur les slides,
 * texte RACONTÉ dans les notes du présentateur.
 * Lancer depuis le dossier "restaurant" :  node docs/build-pptx.cjs
 */
const pptxgen = require("pptxgenjs");
const path = require("path");

const p = new pptxgen();
p.defineLayout({ name: "RS16x9", width: 10, height: 5.625 });
p.layout = "RS16x9";
p.author = "Kamal EL MAGOURI";
p.company = "Riad Saveurs";
p.title = "Riad Saveurs — Présentation du projet";

// Palette marocaine chaude
const TERRA = "B14A28"; // terracotta (primaire)
const TERRA_DK = "8F3A20";
const SAFRAN = "E8A33D";
const OCRE = "C8852A";
const CREAM = "FBF4EA";
const INK = "2B2B2B";
const GREY = "6B6B6B";
const WHITE = "FFFFFF";
const GREEN = "4E7D3A"; // pour les solutions (✅)
const FONT = "Arial";

const W = 10;

// ---------- helpers ----------
function footer(slide, n) {
  slide.addShape(p.ShapeType.rect, { x: 0, y: 5.36, w: W, h: 0.265, fill: { color: CREAM }, line: { type: "none" } });
  slide.addText("Riad Saveurs — Plateforme de commande en ligne", {
    x: 0.4, y: 5.36, w: 6, h: 0.265, fontFace: FONT, fontSize: 9, color: GREY, align: "left", valign: "middle",
  });
  if (n) slide.addText(String(n), {
    x: W - 0.9, y: 5.36, w: 0.5, h: 0.265, fontFace: FONT, fontSize: 9, color: GREY, align: "right", valign: "middle",
  });
}

// Slide de contenu avec bandeau d'en-tête
function contentSlide({ kicker, title, accent = TERRA, pageNo, notes }) {
  const slide = p.addSlide();
  slide.background = { color: WHITE };
  slide.addShape(p.ShapeType.rect, { x: 0, y: 0, w: W, h: 0.95, fill: { color: accent }, line: { type: "none" } });
  slide.addShape(p.ShapeType.rect, { x: 0, y: 0.95, w: W, h: 0.06, fill: { color: SAFRAN }, line: { type: "none" } });
  if (kicker) {
    slide.addText(kicker.toUpperCase(), {
      x: 0.4, y: 0.12, w: W - 0.8, h: 0.28, fontFace: FONT, fontSize: 11, bold: true, color: "FFE2CC", charSpacing: 2,
    });
  }
  slide.addText(title, {
    x: 0.4, y: kicker ? 0.36 : 0.18, w: W - 0.8, h: 0.55, fontFace: FONT, fontSize: 24, bold: true, color: WHITE, valign: "middle",
  });
  if (notes) slide.addNotes(notes);
  footer(slide, pageNo);
  return slide;
}

// Puces stylées
function bullets(slide, items, opts = {}) {
  const x = opts.x ?? 0.55;
  const y = opts.y ?? 1.35;
  const w = opts.w ?? (W - 1.1);
  const h = opts.h ?? 3.7;
  slide.addText(
    items.map((it) => ({
      text: it.text,
      options: {
        bullet: it.bullet === false ? false : { code: "2022", indent: 18 },
        color: it.color || INK,
        bold: !!it.bold,
        fontSize: it.fontSize || 16,
        breakLine: true,
        paraSpaceAfter: it.gap ?? 10,
        indentLevel: it.level || 0,
      },
    })),
    { x, y, w, h, fontFace: FONT, valign: "top", lineSpacingMultiple: 1.05 }
  );
}

// ============================================================
// 1) PAGE DE TITRE
// ============================================================
(function titleSlide() {
  const slide = p.addSlide();
  slide.background = { color: CREAM };
  slide.addShape(p.ShapeType.rect, { x: 0, y: 0, w: 3.5, h: 5.625, fill: { color: TERRA }, line: { type: "none" } });
  slide.addShape(p.ShapeType.rect, { x: 3.5, y: 0, w: 0.08, h: 5.625, fill: { color: SAFRAN }, line: { type: "none" } });
  slide.addText("RIAD", { x: 0.3, y: 1.9, w: 3.0, h: 0.7, fontFace: FONT, fontSize: 40, bold: true, color: WHITE, align: "center", charSpacing: 4 });
  slide.addText("SAVEURS", { x: 0.3, y: 2.55, w: 3.0, h: 0.6, fontFace: FONT, fontSize: 26, color: "FFE2CC", align: "center", charSpacing: 6 });
  slide.addText("Cuisine marocaine\ntraditionnelle", { x: 0.3, y: 3.4, w: 3.0, h: 0.8, fontFace: FONT, fontSize: 13, italic: true, color: "FFE2CC", align: "center" });

  slide.addText("Présentation du projet", { x: 3.95, y: 0.85, w: 5.7, h: 0.4, fontFace: FONT, fontSize: 13, bold: true, color: OCRE, charSpacing: 2 });
  slide.addText("Plateforme de commande\nen ligne", { x: 3.9, y: 1.25, w: 5.85, h: 1.4, fontFace: FONT, fontSize: 32, bold: true, color: INK, lineSpacingMultiple: 1.0 });
  slide.addText("Application web Laravel 12 — Front-office client & Back-office gérant sécurisé, disponible en français, anglais et arabe (RTL).",
    { x: 3.95, y: 2.75, w: 5.7, h: 0.9, fontFace: FONT, fontSize: 13, color: GREY });

  slide.addShape(p.ShapeType.line, { x: 3.95, y: 3.95, w: 5.5, h: 0, line: { color: SAFRAN, width: 1.5 } });
  slide.addText(
    [
      { text: "Réalisé par : ", options: { bold: true, color: INK } },
      { text: "Kamal EL MAGOURI\n", options: { color: INK } },
      { text: "Stack : ", options: { bold: true, color: INK } },
      { text: "Laravel 12 · PHP 8.2+ · Vite 7 · Tailwind CSS 4\n", options: { color: GREY } },
      { text: "Année : ", options: { bold: true, color: INK } },
      { text: "2026", options: { color: GREY } },
    ],
    { x: 3.95, y: 4.1, w: 5.7, h: 1.2, fontFace: FONT, fontSize: 12, lineSpacingMultiple: 1.15 }
  );
  slide.addNotes(
    "Bonjour à tous. Je vais vous présenter Riad Saveurs, une plateforme web de commande en ligne dédiée à la cuisine marocaine traditionnelle, développée avec Laravel 12. Le projet propose une expérience client fluide et un espace de gestion sécurisé pour le gérant, disponible en français, anglais et arabe."
  );
})();

// ============================================================
// 2) PLAN (chiffres romains)
// ============================================================
(function planSlide() {
  const slide = p.addSlide();
  slide.background = { color: WHITE };
  slide.addShape(p.ShapeType.rect, { x: 0, y: 0, w: W, h: 0.95, fill: { color: TERRA }, line: { type: "none" } });
  slide.addShape(p.ShapeType.rect, { x: 0, y: 0.95, w: W, h: 0.06, fill: { color: SAFRAN }, line: { type: "none" } });
  slide.addText("PLAN", { x: 0.4, y: 0.18, w: 9, h: 0.55, fontFace: FONT, fontSize: 26, bold: true, color: WHITE, valign: "middle", charSpacing: 3 });

  const items = [
    ["I", "Introduction"],
    ["II", "Problématique et Solution Proposée"],
    ["III", "Présentation de l'application"],
    ["IV", "Outils Utilisés"],
    ["V", "Conclusion"],
  ];
  let y = 1.3;
  const rowH = 0.74;
  items.forEach(([rn, label], i) => {
    slide.addShape(p.ShapeType.roundRect, { x: 0.8, y: y, w: 0.85, h: 0.56, rectRadius: 0.08, fill: { color: i % 2 ? OCRE : TERRA }, line: { type: "none" } });
    slide.addText(rn, { x: 0.8, y: y, w: 0.85, h: 0.56, fontFace: FONT, fontSize: 20, bold: true, color: WHITE, align: "center", valign: "middle" });
    slide.addText(label, { x: 1.95, y: y, w: 7.3, h: 0.56, fontFace: FONT, fontSize: 18, bold: true, color: INK, valign: "middle" });
    slide.addShape(p.ShapeType.line, { x: 1.95, y: y + 0.63, w: 7.05, h: 0, line: { color: "EADBC4", width: 1 } });
    y += rowH;
  });
  slide.addNotes(
    "Voici le déroulé : d'abord une introduction qui pose le problème de départ et la solution ; puis les quatre problématiques métier détaillées et leurs solutions ; ensuite une présentation de l'application elle-même ; les outils utilisés ; et enfin la conclusion."
  );
  footer(slide, 2);
})();

// ============================================================
// Intercalaire de section
// ============================================================
function sectionDivider(roman, title, pageNo, notes) {
  const slide = p.addSlide();
  slide.background = { color: TERRA };
  slide.addShape(p.ShapeType.rect, { x: 0, y: 2.35, w: W, h: 0.06, fill: { color: SAFRAN }, line: { type: "none" } });
  slide.addText(roman, { x: 0.6, y: 1.5, w: 2.2, h: 1.6, fontFace: FONT, fontSize: 96, bold: true, color: "D98A63", align: "center", valign: "middle" });
  slide.addText(title, { x: 2.9, y: 2.4, w: 6.6, h: 1.4, fontFace: FONT, fontSize: 32, bold: true, color: WHITE, valign: "middle" });
  if (notes) slide.addNotes(notes);
  footer(slide, pageNo);
  return slide;
}

// ============================================================
// I. INTRODUCTION  (petite problématique -> voici la solution)
// ============================================================
sectionDivider("I", "Introduction", 3,
  "J'entre dans la première partie : l'introduction, qui part d'un constat simple pour amener la solution.");

(function besoinSolution() {
  const s = contentSlide({
    kicker: "I. Introduction", title: "Du besoin à la solution", pageNo: 4,
    notes: "Ces dernières années, les habitudes ont changé : la plupart des clients commandent désormais leurs repas en ligne, depuis leur téléphone, plutôt que de se déplacer ou d'appeler le restaurant. Pourtant, beaucoup de restaurants traditionnels, notamment de cuisine marocaine, n'ont toujours pas de canal de commande digital et gèrent encore leurs commandes manuellement. C'est pour cela que notre application Riad Saveurs permet aux clients de découvrir la carte et de commander leurs plats marocains préférés en quelques étapes, sans perdre de temps et sans créer de compte.",
  });
  // Contexte / constat (narratif)
  s.addText("LE CONSTAT", { x: 0.55, y: 1.2, w: 4, h: 0.3, fontFace: FONT, fontSize: 11, bold: true, color: TERRA, charSpacing: 1 });
  s.addShape(p.ShapeType.roundRect, { x: 0.55, y: 1.5, w: W - 1.1, h: 1.7, rectRadius: 0.06, fill: { color: CREAM }, line: { color: TERRA, width: 1.25 } });
  s.addText("Ces dernières années, les habitudes ont changé : la plupart des clients commandent désormais leurs repas en ligne, depuis leur téléphone, plutôt que de se déplacer ou d'appeler. Pourtant, beaucoup de restaurants traditionnels — notamment de cuisine marocaine — n'ont toujours pas de canal de commande digital et gèrent encore leurs commandes manuellement.",
    { x: 0.8, y: 1.55, w: W - 1.6, h: 1.6, fontFace: FONT, fontSize: 14.5, color: INK, valign: "middle", lineSpacingMultiple: 1.05 });

  // Flèche "c'est pour cela"
  s.addText("➜  C'est pour cela que…", { x: 0.55, y: 3.32, w: W - 1.1, h: 0.35, fontFace: FONT, fontSize: 14, bold: true, italic: true, color: GREEN, align: "center" });

  // Solution
  s.addShape(p.ShapeType.roundRect, { x: 0.55, y: 3.75, w: W - 1.1, h: 1.4, rectRadius: 0.06, fill: { color: "EAF1E4" }, line: { color: GREEN, width: 1.25 } });
  s.addText([
    { text: "Riad Saveurs", options: { bold: true, color: GREEN, fontSize: 16 } },
    { text: " permet aux clients de découvrir la carte et de commander leurs plats marocains préférés en quelques étapes, sans perdre de temps et sans créer de compte — et offre au gérant un back-office sécurisé et multilingue (FR / EN / AR).", options: { color: INK, fontSize: 14.5 } },
  ], { x: 0.8, y: 3.8, w: W - 1.6, h: 1.3, fontFace: FONT, valign: "middle", lineSpacingMultiple: 1.05 });
})();

(function introObjectives() {
  const s = contentSlide({
    kicker: "I. Introduction", title: "Objectifs du produit", pageNo: 5,
    notes: "Le projet poursuit quatre objectifs : offrir une expérience client sans friction en quatre étapes ; doter le gérant d'un outil de gestion autonome et sécurisé en temps réel ; rendre le service accessible à l'international grâce au multilingue ; et valoriser le patrimoine culinaire marocain par un design soigné aux couleurs chaudes.",
  });
  bullets(s, [
    { text: "Expérience client fluide : menu → panier → commande → suivi, sans inscription.", bold: true },
    { text: "Outil de gestion autonome et sécurisé pour le gérant, en temps réel.", bold: true },
    { text: "Accessibilité internationale : interface en français, anglais et arabe.", bold: true },
    { text: "Valorisation du patrimoine culinaire marocain (terracotta, safran, ocre).", bold: true },
  ], { y: 1.45, gap: 16 });
  s.addShape(p.ShapeType.roundRect, { x: 0.55, y: 4.4, w: W - 1.1, h: 0.6, rectRadius: 0.06, fill: { color: CREAM }, line: { type: "none" } });
  s.addText([
    { text: "Public visé : ", options: { bold: true, color: TERRA } },
    { text: "clients amateurs de cuisine marocaine  ·  gérant et personnel du restaurant.", options: { color: INK } },
  ], { x: 0.75, y: 4.4, w: W - 1.5, h: 0.6, fontFace: FONT, fontSize: 14, valign: "middle" });
})();

// ============================================================
// II. PROBLÉMATIQUE ET SOLUTION PROPOSÉE
// ============================================================
sectionDivider("II", "Problématique et\nSolution Proposée", 6,
  "Deuxième partie, le cœur du sujet : les quatre problématiques métier rencontrées et les solutions techniques proposées.");

(function synthese() {
  const s = contentSlide({
    kicker: "II. Problématique et solution proposée", title: "Vue d'ensemble : 4 problématiques · 4 solutions", pageNo: 7,
    notes: "Nous avons identifié quatre problématiques métier majeures. Pour chacune, une solution concrète a été mise en œuvre. Ce tableau les résume avant le détail.",
  });
  const rows = [
    [{ text: "#", options: { bold: true, color: WHITE, fill: { color: TERRA_DK } } },
     { text: "Problématique", options: { bold: true, color: WHITE, fill: { color: TERRA } } },
     { text: "Solution proposée", options: { bold: true, color: WHITE, fill: { color: GREEN } } }],
    ["1", "Commander sans friction", "Parcours 4 étapes + panier en session (AJAX)"],
    ["2", "Accès gérant non protégé", "Authentification + rôles admin / operator"],
    ["3", "Intégrité des commandes", "Prix figés + transaction + historique"],
    ["4", "Public international", "Internationalisation FR / EN / AR (RTL)"],
  ].map((r) =>
    r.map((c) => (typeof c === "string" ? { text: c, options: { color: INK } } : c))
  );
  s.addTable(rows, {
    x: 0.55, y: 1.5, w: W - 1.1, colW: [0.7, 3.7, 4.5], rowH: 0.62,
    fontFace: FONT, fontSize: 13, valign: "middle", align: "left",
    border: { type: "solid", color: "EADBC4", pt: 1 }, fill: { color: WHITE },
  });
})();

// Cas problématique / solution
function problemSlide({ num, titre, pageNo, points, notes }) {
  const s = contentSlide({ kicker: `II. Cas ${num} — Problématique`, title: `${num}️⃣  ${titre}`, accent: TERRA, pageNo, notes });
  s.addShape(p.ShapeType.roundRect, { x: 0.55, y: 1.45, w: 1.5, h: 1.5, rectRadius: 0.1, fill: { color: CREAM }, line: { color: TERRA, width: 1 } });
  s.addText("❓", { x: 0.55, y: 1.5, w: 1.5, h: 1.4, fontSize: 54, align: "center", valign: "middle" });
  s.addText("PROBLÉMATIQUE", { x: 0.55, y: 2.78, w: 1.5, h: 0.25, fontFace: FONT, fontSize: 9, bold: true, color: TERRA, align: "center", charSpacing: 1 });
  bullets(s, points.map((t) => ({ text: t, fontSize: 17, gap: 14 })), { x: 2.4, y: 1.7, w: 7.1, h: 3.2 });
}

function solutionSlide({ num, titre, pageNo, points, notes }) {
  const s = contentSlide({ kicker: `II. Cas ${num} — Solution`, title: `${num}️⃣  ${titre}`, accent: GREEN, pageNo, notes });
  s.addShape(p.ShapeType.roundRect, { x: 0.55, y: 1.45, w: 1.5, h: 1.5, rectRadius: 0.1, fill: { color: "EAF1E4" }, line: { color: GREEN, width: 1 } });
  s.addText("✅", { x: 0.55, y: 1.5, w: 1.5, h: 1.4, fontSize: 54, align: "center", valign: "middle" });
  s.addText("SOLUTION", { x: 0.55, y: 2.78, w: 1.5, h: 0.25, fontFace: FONT, fontSize: 9, bold: true, color: GREEN, align: "center", charSpacing: 1 });
  bullets(s, points.map((t) => ({ text: t, fontSize: 15, gap: 11 })), { x: 2.4, y: 1.55, w: 7.1, h: 3.5 });
}

// Cas 1
problemSlide({
  num: 1, titre: "Commander sans friction", pageNo: 8,
  points: [
    "Obliger un client à créer un compte avant de commander provoque de l'abandon.",
    "Le parcours d'achat doit être simple, rapide et sans barrière à l'entrée.",
  ],
  notes: "Première problématique : le parcours d'achat. Imposer la création d'un compte génère des abandons. L'objectif était un parcours aussi simple et rapide que possible.",
});
solutionSlide({
  num: 1, titre: "Parcours simplifié, sans compte", pageNo: 9,
  points: [
    "Parcours en 4 étapes : menu → panier → commande → suivi.",
    "Panier stocké en session côté serveur, recalculé au prix actuel des plats.",
    "Ajout et quantités en AJAX : compteur, sous-totaux et total mis à jour sans rechargement (repli formulaire si JavaScript absent).",
    "Client identifié par son numéro de téléphone (firstOrCreate) : pas de doublon, pas de mot de passe.",
  ],
  notes: "La solution : un parcours en quatre étapes sans inscription. Le panier vit en session. L'ajout et les quantités se font en AJAX, donc sans rechargement. Et le client est identifié par son téléphone, ce qui évite les doublons sans imposer de mot de passe.",
});
// Cas 2
problemSlide({
  num: 2, titre: "Accès gérant non protégé", pageNo: 10,
  points: [
    "L'ancien accès /admin était libre : n'importe qui pouvait gérer la carte et les commandes.",
    "➡  Risque de sécurité majeur — point bloquant à corriger.",
  ],
  notes: "Deuxième problématique, un point bloquant : l'accès admin était totalement libre. N'importe quel visiteur pouvait gérer la carte et les commandes. Risque de sécurité majeur.",
});
solutionSlide({
  num: 2, titre: "Authentification + contrôle de rôle", pageNo: 11,
  points: [
    "Authentification Laravel (garde web) avec écran de connexion dédié.",
    "Deux rôles : admin (accès complet) et operator (commandes uniquement).",
    "Middlewares de rôle renvoyant une erreur 403 si l'accès n'est pas autorisé.",
    "Durcissement : throttle 6/min à la connexion, régénération de session, vérification du rôle (un client est refusé), role hors $fillable.",
  ],
  notes: "La solution s'appuie sur l'authentification Laravel avec un écran de connexion. Deux rôles : admin et operator, limité aux commandes. Des middlewares renvoient une 403 si besoin. Et plusieurs mesures de durcissement : limitation des tentatives, régénération de session, vérification du rôle, protection contre le mass-assignment.",
});
// Cas 3
problemSlide({
  num: 3, titre: "Intégrité des commandes", pageNo: 12,
  points: [
    "Un changement de prix d'un plat ne doit pas altérer les commandes déjà passées.",
    "Une commande ne doit jamais être enregistrée à moitié (erreur en cours d'écriture).",
  ],
  notes: "Troisième problématique : l'intégrité des données. Si le gérant modifie un prix, les commandes passées ne doivent pas changer. Et une commande ne doit jamais être enregistrée partiellement.",
});
solutionSlide({
  num: 3, titre: "Prix figés, transaction, historique", pageNo: 13,
  points: [
    "Prix figés : le prix unitaire est copié dans la ligne de commande au moment de l'achat.",
    "Transaction atomique au checkout : la commande et ses lignes, ou rien.",
    "Historique des statuts (details_statuses) journalisant chaque transition.",
    "Suppression défensive : impossible de supprimer un plat déjà commandé ou une catégorie non vide.",
  ],
  notes: "On fige le prix unitaire dans la ligne au moment de l'achat. La création se fait dans une transaction atomique : tout ou rien. Chaque changement de statut est journalisé. Et une suppression défensive empêche d'effacer un plat commandé ou une catégorie non vide.",
});
// Cas 4
problemSlide({
  num: 4, titre: "Servir un public international", pageNo: 14,
  points: [
    "L'interface doit être disponible en plusieurs langues.",
    "Dont l'arabe, écrit de droite à gauche (RTL), avec des contraintes d'affichage spécifiques.",
  ],
  notes: "Dernière problématique : le restaurant vise une clientèle variée. L'interface devait être multilingue, et notamment en arabe, qui s'écrit de droite à gauche.",
});
solutionSlide({
  num: 4, titre: "Internationalisation FR / EN / AR (RTL)", pageNo: 15,
  points: [
    "Traductions JSON de Laravel (__()) avec le français comme clé : maintenance minimale.",
    "Support RTL complet pour l'arabe (dir=\"rtl\" + ajustements CSS dédiés).",
    "Middleware SetLocale : langue mémorisée en session et dates localisées (Carbon).",
    "Sélecteur FR · EN · AR présent partout, messages de validation localisés.",
  ],
  notes: "L'interface est traduite en français, anglais et arabe, avec le français comme clé pour réduire la maintenance. Le support droite-à-gauche est complet. Un middleware applique la langue à chaque requête et localise les dates. Un sélecteur de langue est présent partout.",
});

// ============================================================
// III. PRÉSENTATION DE L'APPLICATION
// ============================================================
sectionDivider("III", "Présentation de\nl'application", 16,
  "Troisième partie : la présentation de l'application elle-même, ses deux espaces et leurs fonctionnalités.");

(function deuxEspaces() {
  const s = contentSlide({
    kicker: "III. Présentation de l'application", title: "Une application, deux espaces", pageNo: 17,
    notes: "L'application s'organise en deux espaces complémentaires : un espace client, la vitrine gastronomique tournée vers la commande ; et un espace gérant, un back-office protégé pour piloter tout le restaurant.",
  });
  s.addText("L'application se compose de deux espaces complémentaires, séparés et avec des accès distincts.",
    { x: 0.55, y: 1.25, w: W - 1.1, h: 0.5, fontFace: FONT, fontSize: 15, italic: true, color: GREY });
  const cards = [
    { t: "🍽  Espace client (front-office)", c: TERRA, lines: "Vitrine gastronomique : parcourir la carte par catégories, consulter le détail d'un plat (ingrédients, temps, prix), remplir un panier, commander et suivre l'avancement — sans inscription." },
    { t: "🔐  Espace gérant (back-office)", c: OCRE, lines: "Tableau de bord protégé par authentification : gestion de la carte, des catégories, des clients, des commandes et de l'équipe, avec des indicateurs d'activité en temps réel." },
  ];
  cards.forEach((card, i) => {
    const x = 0.55 + i * 4.55;
    s.addShape(p.ShapeType.roundRect, { x, y: 1.9, w: 4.35, h: 3.0, rectRadius: 0.06, fill: { color: CREAM }, line: { color: card.c, width: 1.25 } });
    s.addShape(p.ShapeType.rect, { x, y: 1.9, w: 4.35, h: 0.12, fill: { color: card.c }, line: { type: "none" } });
    s.addText(card.t, { x: x + 0.2, y: 2.15, w: 3.95, h: 0.55, fontFace: FONT, fontSize: 15, bold: true, color: card.c });
    s.addText(card.lines, { x: x + 0.2, y: 2.75, w: 3.95, h: 2.0, fontFace: FONT, fontSize: 13, color: INK, valign: "top" });
  });
})();

(function parcoursClient() {
  const s = contentSlide({
    kicker: "III. Présentation de l'application", title: "Espace client : un parcours en 4 étapes", accent: TERRA, pageNo: 18,
    notes: "Côté client, le parcours se déroule en quatre étapes. D'abord le menu, organisé par catégories avec recherche. Ensuite le détail d'un plat, avec ses ingrédients et des plats similaires. Puis le panier, en session, modifiable en AJAX. Et enfin la commande avec l'adresse de livraison, suivie d'un écran de suivi du statut, étape par étape.",
  });
  const steps = [
    { n: "1", t: "Menu", d: "Plats groupés par catégorie, barre de recherche, plats disponibles en priorité." },
    { n: "2", t: "Panier", d: "Stocké en session, quantités modifiables en AJAX, total recalculé en direct." },
    { n: "3", t: "Commande", d: "Adresse + destinataire, client retrouvé par téléphone, enregistrement en transaction." },
    { n: "4", t: "Suivi", d: "Recherche par n° + téléphone, frise des statuts horodatée, accès protégé (403)." },
  ];
  steps.forEach((st, i) => {
    const x = 0.55 + i * 2.28;
    s.addShape(p.ShapeType.roundRect, { x, y: 1.55, w: 2.05, h: 3.2, rectRadius: 0.06, fill: { color: CREAM }, line: { color: "EADBC4", width: 1 } });
    s.addShape(p.ShapeType.ellipse, { x: x + 0.72, y: 1.78, w: 0.62, h: 0.62, fill: { color: i % 2 ? OCRE : TERRA }, line: { type: "none" } });
    s.addText(st.n, { x: x + 0.72, y: 1.78, w: 0.62, h: 0.62, fontFace: FONT, fontSize: 22, bold: true, color: WHITE, align: "center", valign: "middle" });
    s.addText(st.t, { x: x + 0.1, y: 2.5, w: 1.85, h: 0.4, fontFace: FONT, fontSize: 15, bold: true, color: INK, align: "center" });
    s.addText(st.d, { x: x + 0.18, y: 2.95, w: 1.7, h: 1.7, fontFace: FONT, fontSize: 11.5, color: GREY, align: "center", valign: "top" });
    if (i < 3) s.addText("›", { x: x + 1.95, y: 1.55, w: 0.5, h: 3.2, fontFace: FONT, fontSize: 30, bold: true, color: SAFRAN, align: "center", valign: "middle" });
  });
})();

(function backOffice() {
  const s = contentSlide({
    kicker: "III. Présentation de l'application", title: "Espace gérant : le back-office", accent: OCRE, pageNo: 19,
    notes: "Côté gérant, le back-office offre un tableau de bord avec les indicateurs clés — chiffre d'affaires, top des plats, commandes par statut. Le gérant gère la carte, les catégories et les clients en CRUD complet. Il pilote les commandes et leurs statuts en temps réel. Et il gère l'équipe : création de comptes gérants ou opérateurs, activation ou désactivation.",
  });
  bullets(s, [
    { text: "Tableau de bord : CA du jour et total, top 5 des plats, CA des 7 derniers jours, répartition par statut.", bold: true },
    { text: "Gestion de la carte : CRUD des plats (prix, disponibilité, image par upload ou URL), recherche et filtre." },
    { text: "Catégories & clients : CRUD complet, recherche multi-champs, garde-fous d'intégrité." },
    { text: "Commandes : liste filtrable, détail, changement de statut en temps réel (journalisé)." },
    { text: "Équipe : comptes admin / operator, création, édition, activation / désactivation." },
  ], { y: 1.4, gap: 12, fontSize: 15 });
})();

// ============================================================
// IV. OUTILS UTILISÉS
// ============================================================
sectionDivider("IV", "Outils Utilisés", 20,
  "Quatrième partie : les outils et technologies utilisés.");

(function tools() {
  const s = contentSlide({
    kicker: "IV. Outils utilisés", title: "Pile technique", pageNo: 21,
    notes: "Le backend repose sur PHP 8.2 et Laravel 12 avec l'ORM Eloquent. Le frontend utilise Blade, Vite 7 et Tailwind CSS 4, avec du JavaScript vanilla pour l'AJAX. La base est SQLite par défaut, avec bascule MySQL possible. L'authentification, le multilingue et les tests s'appuient sur les briques standard de Laravel et PHPUnit.",
  });
  const rows = [
    [{ text: "Couche", options: { bold: true, color: WHITE, fill: { color: TERRA } } },
     { text: "Technologie", options: { bold: true, color: WHITE, fill: { color: TERRA } } }],
    ["Backend", "PHP 8.2+ · Laravel 12 · Eloquent ORM"],
    ["Frontend", "Blade · Vite 7 · Tailwind CSS 4 · JavaScript vanilla (AJAX)"],
    ["Base de données", "SQLite (par défaut) / MySQL"],
    ["Authentification", "Garde web Laravel + middlewares de rôle"],
    ["Internationalisation", "Traductions JSON __() + middleware de locale"],
    ["Tests / Dev", "PHPUnit 11 · Laravel Pail · Pint · Sail"],
  ].map((r, i) => r.map((c) => (typeof c === "string" ? { text: c, options: { color: INK, bold: i > 0 && r.indexOf(c) === 0 } } : c)));
  s.addTable(rows, {
    x: 0.55, y: 1.45, w: W - 1.1, colW: [2.6, 6.3], rowH: 0.52,
    fontFace: FONT, fontSize: 13, valign: "middle", align: "left",
    border: { type: "solid", color: "EADBC4", pt: 1 }, fill: { color: CREAM },
  });
})();

(function whyTools() {
  const s = contentSlide({
    kicker: "IV. Outils utilisés", title: "Justification des choix", pageNo: 22,
    notes: "Laravel apporte un cadre MVC robuste avec sécurité intégrée. SQLite permet un démarrage immédiat sans serveur de base de données. Tailwind et Vite offrent un design rapide et responsive. Le panier en session évite une table superflue. Et l'internationalisation par clés françaises minimise les fichiers à maintenir.",
  });
  bullets(s, [
    { text: "Laravel 12 (MVC) — robuste, sécurité intégrée (CSRF, validation, hachage).", bold: true },
    { text: "SQLite par défaut — démarrage immédiat ; bascule MySQL par configuration." },
    { text: "Tailwind 4 + Vite 7 — design rapide, cohérent et responsive." },
    { text: "Panier en session — éphémère, aucune table superflue." },
    { text: "i18n par clés françaises — le français reste le repli naturel, fichiers minimaux." },
  ], { y: 1.5, gap: 15 });
})();

// ============================================================
// V. CONCLUSION
// ============================================================
sectionDivider("V", "Conclusion", 23,
  "J'arrive à la conclusion.");

(function conclusion() {
  const s = contentSlide({
    kicker: "V. Conclusion", title: "Une application complète, sécurisée et multilingue", pageNo: 24,
    notes: "Pour conclure, Riad Saveurs est une application fonctionnellement complète, sécurisée et multilingue. Elle couvre tout le parcours client et toute la gestion gérant en appliquant de bonnes pratiques. Surtout, le point bloquant identifié au départ — l'absence de protection du back-office — est désormais résolu.",
  });
  bullets(s, [
    { text: "Parcours client complet : menu → panier → commande → suivi.", color: GREEN, bold: true },
    { text: "Gestion gérant complète : carte, catégories, clients, commandes, tableau de bord.", color: GREEN, bold: true },
    { text: "Bonnes pratiques : transactions, prix figés, anti mass-assignment, validation, upload sécurisé, seeders idempotents.", color: GREEN, bold: true },
  ], { y: 1.45, gap: 14 });
  s.addShape(p.ShapeType.roundRect, { x: 0.55, y: 3.85, w: W - 1.1, h: 1.05, rectRadius: 0.06, fill: { color: CREAM }, line: { color: TERRA, width: 1 } });
  s.addText([
    { text: "Point bloquant résolu  —  ", options: { bold: true, color: TERRA } },
    { text: "les routes /admin sont désormais protégées par authentification et contrôle de rôle, avec un écran de connexion dédié. L'application est prête à être exploitée.", options: { color: INK } },
  ], { x: 0.8, y: 3.9, w: W - 1.6, h: 0.95, fontFace: FONT, fontSize: 13.5, valign: "middle" });
})();

(function perspectives() {
  const s = contentSlide({
    kicker: "V. Conclusion", title: "Perspectives d'évolution", pageNo: 25,
    notes: "Les évolutions restantes relèvent du confort fonctionnel et non d'un prérequis de mise en production : notifications au client, paiement en ligne, espace client authentifié, ou traduction du contenu de la carte.",
  });
  const rows = [
    [{ text: "Priorité", options: { bold: true, color: WHITE, fill: { color: TERRA } } },
     { text: "Amélioration", options: { bold: true, color: WHITE, fill: { color: TERRA } } }],
    ["🟠  Moyenne", "Notifications client (email / SMS) au changement de statut"],
    ["🟡  Basse", "Paiement en ligne"],
    ["🟡  Basse", "Espace client authentifié avec historique de commandes"],
    ["🟡  Basse", "Traduction du contenu de la carte (base de données)"],
  ].map((r) => r.map((c) => (typeof c === "string" ? { text: c, options: { color: INK } } : c)));
  s.addTable(rows, {
    x: 0.55, y: 1.5, w: W - 1.1, colW: [2.3, 6.6], rowH: 0.62,
    fontFace: FONT, fontSize: 13, valign: "middle", align: "left",
    border: { type: "solid", color: "EADBC4", pt: 1 }, fill: { color: WHITE },
  });
})();

// ============================================================
// MERCI
// ============================================================
(function thanks() {
  const slide = p.addSlide();
  slide.background = { color: TERRA };
  slide.addShape(p.ShapeType.rect, { x: 0, y: 3.05, w: W, h: 0.06, fill: { color: SAFRAN }, line: { type: "none" } });
  slide.addText("Merci de votre attention", { x: 0.5, y: 1.9, w: 9, h: 0.9, fontFace: FONT, fontSize: 38, bold: true, color: WHITE, align: "center" });
  slide.addText("Riad Saveurs — Cuisine marocaine traditionnelle  ·  Laravel 12  ·  FR · EN · AR", { x: 0.5, y: 3.2, w: 9, h: 0.5, fontFace: FONT, fontSize: 15, color: "FFE2CC", align: "center" });
  slide.addText("Questions ?", { x: 0.5, y: 3.95, w: 9, h: 0.6, fontFace: FONT, fontSize: 20, italic: true, bold: true, color: SAFRAN, align: "center" });
  slide.addNotes("Je vous remercie de votre attention et je suis à votre disposition pour répondre à vos questions.");
})();

// ---------- écriture ----------
const out = path.join(__dirname, "PRESENTATION.pptx");
p.writeFile({ fileName: out }).then((f) => console.log("OK ->", f));
