<?php

declare(strict_types=1);

/**
 * Okala Crawler
 * - Fetch nearby products per store + category
 * - Cache responses as HTML/JSON files
 * - Export merged products as single JSON
 */

date_default_timezone_set('Asia/Tehran');

/* ---------------- CONFIG ---------------- */

const LAT = 35.805851;
const LON = 51.431311;

const CACHE_DIR = __DIR__ . '/cache';
const OUTPUT_FILE = __DIR__ . '/products.json';

$STORES = [
    "2319","10458","9871","9652","2318","10381","9020","9768",
    "8840","5989","10650","7500","8131","9867","7791","8729",
    "9991","8662"
];

$CATEGORIES = [
    ["kalabarg", 1467],
    ["refreshments", 1467],
    ["dairy-products", 1462],
    ["groceries", 1461],
    ["home-hygiene", 1471],
    ["beverages", 1465],
    ["spices", 1469],
    ["canned-ready-food", 1464],
    ["cosmetics-hygiene", 1472],
    ["proteins", 1463],
    ["breakfast-goods", 1466],
    ["home-stuff", 1473],
    ["baby-mother-care", 1474],
    ["fruits-vegetables", 1470],
    ["nuts-sweets", 1468],
    ["multiples", 1850],
];

/* --------------- BOOTSTRAP --------------- */

if (!is_dir(CACHE_DIR)) {
    mkdir(CACHE_DIR, 0777, true);
}

/* ---------------- HTTP CLIENT ---------------- */

function httpGet(string $url, array $headers = []): ?array
{
    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);

    curl_close($ch);

    if ($response === false || $httpCode !== 200) {
        return null;
    }

    return json_decode($response, true);
}

/* ---------------- CACHE ---------------- */

function cacheWrite(string $path, string $content): void
{
    file_put_contents($path, $content);
}

function cacheRead(string $path): ?string
{
    if (!file_exists($path)) return null;
    return file_get_contents($path);
}

/* ---------------- API CALLS ---------------- */

function fetchNearby(string $slug): ?array
{
    $url = "https://apigateway.okala.com/api/unicorn/v2/products/nearby"
        . "?slug={$slug}&lat=" . LAT . "&lon=" . LON;

    $headers = [
        "Accept: application/json",
        "X-Skip-Authorization: false",
        "source: okala",
    ];

    return httpGet($url, $headers);
}

function fetchPdp(int $storeId, int $productId): ?array
{
    $url = "https://apigateway.okala.com/api/Unicorn/v1/catalog/pdp"
        . "?sId={$storeId}&pId={$productId}";

    $headers = [
        "Accept: application/json",
        "X-Skip-Authorization: false",
        "source: okala",
    ];

    return httpGet($url, $headers);
}

/* ---------------- CORE LOGIC ---------------- */

$seenProducts = [];
$finalProducts = [];

foreach ($CATEGORIES as [$slug, $catId]) {

    echo "Fetching category: {$slug}\n";

    $cacheFile = CACHE_DIR . "/nearby_{$slug}.json";

    $data = cacheRead($cacheFile);

    if ($data) {
        $json = json_decode($data, true);
    } else {
        $json = fetchNearby($slug);
        if ($json) {
            cacheWrite($cacheFile, json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
    }

    if (!$json || !isset($json['data'])) {
        continue;
    }

    foreach ($json['data'] as $storeBlock) {

        $store = $storeBlock['store'] ?? null;
        $products = $storeBlock['products'] ?? [];

        if (!$store) continue;

        $storeId = (int)$store['storeId'];

        foreach ($products as $p) {

            $masterId = $p['masterProductId'] ?? null;
            if (!$masterId) continue;

            // Deduplicate globally
            if (isset($seenProducts[$masterId])) {
                continue;
            }

            $seenProducts[$masterId] = true;

            // optional: fetch PDP for richer data
            $pdp = fetchPdp($storeId, (int)$p['id']);

            $finalProducts[] = [
                "masterProductId" => $masterId,
                "id" => $p['id'],
                "storeId" => $storeId,
                "storeName" => $store['storeName'] ?? null,
                "name" => $p['name'] ?? null,
                "price" => $p['price'] ?? null,
                "okPrice" => $p['okPrice'] ?? null,
                "discountPercent" => $p['discountPercent'] ?? 0,
                "quantity" => $p['quantity'] ?? 0,
                "imageUrl" => $p['imageUrl'] ?? null,
                "categorySlug" => $slug,
                "pdp" => $pdp ?? null,
            ];
        }
    }
}

/* ---------------- OUTPUT ---------------- */

file_put_contents(
    OUTPUT_FILE,
    json_encode($finalProducts, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
);

echo "Done. Exported " . count($finalProducts) . " products.\n";
