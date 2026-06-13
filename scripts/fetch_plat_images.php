<?php

/**
 * Télécharge une vraie photo (sous licence libre) pour chaque plat depuis
 * Wikimedia Commons et l'enregistre dans public/images/plats/<slug>.<ext>.
 *
 * Usage : php scripts/fetch_plat_images.php
 */

$dest = __DIR__.'/../public/images/plats';
if (! is_dir($dest)) {
    mkdir($dest, 0775, true);
}

$ua = 'RiadSaveurs/1.0 (freelance demo; contact admin@riad.test)';

/**
 * slug => liste de termes de recherche (du plus précis au plus générique).
 */
$dishes = [
    // Entrées
    'harira'           => ['Harira soup', 'Harira', 'Moroccan soup'],
    'briouates'        => ['Briouat', 'Briouates', 'Moroccan brick pastry'],
    'salade-marocaine' => ['Moroccan salad tomato', 'Moroccan salad', 'Tomato cucumber salad'],
    'zaalouk'          => ['Zaalouk', 'Moroccan eggplant salad', 'Aubergine dip'],
    // Plats principaux
    'tagine-agneau'    => ['Lamb tagine prunes', 'Lamb tagine', 'Moroccan tajine meat'],
    'tagine-poulet'    => ['Chicken tagine preserved lemon olives', 'Chicken tagine', 'Moroccan chicken tajine'],
    'pastilla'         => ['Pastilla Moroccan', 'Bastilla', 'Moroccan pie pastilla'],
    'rfissa'           => ['Rfissa', 'Moroccan rfissa chicken', 'Moroccan lentil chicken dish'],
    'kefta'            => ['Kefta tagine eggs', 'Moroccan meatballs tomato', 'Kefta mkaouara'],
    'mechoui'          => ['Mechoui lamb', 'Roast lamb shoulder', 'Moroccan roasted lamb'],
    // Couscous
    'couscous-legumes' => ['Couscous vegetables', 'Moroccan couscous seven vegetables', 'Couscous'],
    'couscous-tfaya'   => ['Couscous tfaya', 'Couscous caramelized onions raisins', 'Sweet couscous'],
    'couscous-boeuf'   => ['Couscous beef', 'Couscous meat', 'Moroccan couscous'],
    // Desserts
    'chebakia'         => ['Chebakia', 'Moroccan honey sesame cookie', 'Chebakia mkharka'],
    'cornes-gazelle'   => ['Kaab el ghazal', 'Gazelle horns pastry', 'Moroccan almond crescent'],
    'sellou'           => ['Sellou', 'Sfouf Moroccan', 'Moroccan roasted flour sweet'],
    'salade-oranges'   => ['Orange cinnamon salad', 'Sliced oranges cinnamon dessert', 'Orange salad'],
    // Thés & Boissons
    'the-menthe'       => ['Maghrebi mint tea', 'Moroccan mint tea glass', 'Mint tea'],
    'jus-avocat'       => ['Avocado smoothie glass', 'Avocado juice', 'Avocado milkshake'],
    'qahwa'            => ['Arabic coffee cup', 'Black coffee cup', 'Spiced coffee'],
];

/** Appel API JSON avec User-Agent. */
function api(string $url, string $ua): ?array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => $ua,
        CURLOPT_TIMEOUT => 30,
    ]);
    $body = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($code !== 200 || ! $body) {
        return null;
    }

    return json_decode($body, true);
}

/** Cherche une image JPEG/PNG/WebP raisonnable pour un terme. */
function findImage(string $term, string $ua): ?array
{
    $url = 'https://commons.wikimedia.org/w/api.php?'.http_build_query([
        'action' => 'query',
        'generator' => 'search',
        'gsrsearch' => $term,
        'gsrnamespace' => 6,
        'gsrlimit' => 8,
        'prop' => 'imageinfo',
        'iiprop' => 'url|mime|size|extmetadata',
        'iiurlwidth' => 900,
        'format' => 'json',
    ]);

    $data = api($url, $ua);
    $pages = $data['query']['pages'] ?? [];
    if (! $pages) {
        return null;
    }

    // Trie par index de pertinence renvoyé par l'API.
    usort($pages, fn ($a, $b) => ($a['index'] ?? 99) <=> ($b['index'] ?? 99));

    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

    foreach ($pages as $page) {
        $info = $page['imageinfo'][0] ?? null;
        if (! $info) {
            continue;
        }
        $mime = $info['mime'] ?? '';
        if (! isset($allowed[$mime])) {
            continue;
        }
        // Évite les images trop petites / icônes.
        if (($info['width'] ?? 0) < 600) {
            continue;
        }
        $thumb = $info['thumburl'] ?? $info['url'] ?? null;
        if (! $thumb) {
            continue;
        }

        return [
            'url' => $thumb,
            'ext' => $allowed[$mime],
            'title' => $page['title'] ?? '',
        ];
    }

    return null;
}

/** Télécharge et valide une image. */
function download(string $url, string $path, string $ua): bool
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => $ua,
        CURLOPT_TIMEOUT => 60,
    ]);
    $bytes = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($code !== 200 || ! $bytes || strlen($bytes) < 3000) {
        return false;
    }
    $size = @getimagesizefromstring($bytes);
    if (! $size || $size[0] < 400) {
        return false;
    }

    return (bool) file_put_contents($path, $bytes);
}

$ok = 0;
$fail = [];

foreach ($dishes as $slug => $terms) {
    $found = null;
    foreach ($terms as $term) {
        $found = findImage($term, $ua);
        if ($found) {
            break;
        }
        usleep(200000);
    }

    if (! $found) {
        $fail[] = $slug;
        fwrite(STDERR, "  [MISS] {$slug}\n");
        continue;
    }

    $path = "{$GLOBALS['dest']}/{$slug}.{$found['ext']}";
    if (download($found['url'], $path, $ua)) {
        $ok++;
        echo "  [OK]  {$slug}.{$found['ext']}  <- {$found['title']}\n";
    } else {
        $fail[] = $slug;
        fwrite(STDERR, "  [DL-FAIL] {$slug} ({$found['url']})\n");
    }
    usleep(150000);
}

echo "\nTéléchargées : {$ok}/".count($dishes)."\n";
if ($fail) {
    echo 'Échecs : '.implode(', ', $fail)."\n";
}
