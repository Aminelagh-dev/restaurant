<?php

/**
 * Extrait toutes les clés de traduction __('...') / trans_choice('...')
 * des vues Blade et du code PHP, puis les liste (triées, uniques) en JSON.
 * Usage : php scripts/i18n_extract.php
 */

$roots = [__DIR__ . '/../resources/views', __DIR__ . '/../app'];
$keys = [];

$rii = function (string $dir) {
    return new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)
    );
};

// Capture le 1er argument littéral de __() ou trans_choice().
// Gère les guillemets simples et doubles, avec échappements.
$pattern = '/(?:__|trans_choice)\(\s*((?:\'(?:[^\'\\\\]|\\\\.)*\')|(?:"(?:[^"\\\\]|\\\\.)*"))/';

foreach ($roots as $root) {
    if (! is_dir($root)) {
        continue;
    }
    foreach ($rii($root) as $file) {
        if (! preg_match('/\.(php|blade\.php)$/', $file->getFilename())) {
            continue;
        }
        $code = file_get_contents($file->getPathname());
        if (preg_match_all($pattern, $code, $m)) {
            foreach ($m[1] as $raw) {
                $quote = $raw[0];
                $inner = substr($raw, 1, -1);
                // Dé-échappe selon le type de guillemet.
                if ($quote === "'") {
                    $inner = str_replace(["\\'", '\\\\'], ["'", '\\'], $inner);
                } else {
                    $inner = stripcslashes($inner);
                }
                $keys[$inner] = true;
            }
        }
    }
}

$keys = array_keys($keys);
sort($keys, SORT_STRING);

echo json_encode($keys, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), "\n";
echo "\n// Total: " . count($keys) . " clés\n";
