<?php

declare(strict_types=1);

date_default_timezone_set('Asia/Tehran');

/* ---------------- CONFIG ---------------- */
const LAT = 35.805851;
const LON = 51.431311;

const CACHE_DIR = __DIR__ . '/cache';
const CACHE_TTL_NEARBY = 3600;      // 1 hour
const CACHE_TTL_PDP = 86400;        // 24 hours
const CACHE_TTL_STORECAT = 8640;    // 2.4 hours

const PRODUCT_OUTPUT_FILE = __DIR__ . '/products.json';
const CATEGORY_OUTPUT_FILE = __DIR__ . '/categories.json';

const HTTP_TIMEOUT = 25;
const MAX_RETRIES = 3;
const RETRY_BASE_DELAY_MS = 400;

$STORES = [
    2319,
    10458,
    9871,
    9652,
    2318,
    10381,
    9020,
    9768,
    8840,
    5989,
    10650,
    7500,
    8131,
    9867,
    7791,
    8729,
    9991,
    8662,
];

$CATEGORIES = [
    ["kalabarg",           1467],
    ["refreshments",       1467],
    ["dairy-products",     1462],
    ["groceries",          1461],
    ["home-hygiene",       1471],
    ["beverages",          1465],
    ["spices",             1469],
    ["canned-ready-food",  1464],
    ["cosmetics-hygiene",  1472],
    ["proteins",           1463],
    ["breakfast-goods",    1466],
    ["home-stuff",         1473],
    ["baby-mother-care",   1474],
    ["fruits-vegetables",  1470],
    ["nuts-sweets",        1468],
    ["multiples",          1850],
];

/* ---------------- BOOTSTRAP ---------------- */
if (!is_dir(CACHE_DIR)) {
    mkdir(CACHE_DIR, 0777, true);
}

/* ---------------- UTIL ---------------- */
function logMsg(string $msg): void {
    echo "[" . date('H:i:s') . "] $msg\n";
}

function shaKey(string $input): string {
    return sha1($input);
}

function buildStoreIdsQuery(array $storeIds)
{
    $parts = [];
    foreach ($storeIds as $id) {
        $parts[] = "storeIds=" . urlencode((string)$id);
    }
    return implode("&", $parts);
}

/* ---------------- CACHE LAYER ---------------- */
function cachePath(string $prefix, string $key): string {
    return CACHE_DIR . "/{$prefix}_" . $key . ".json";
}

function cacheGet(string $path, int $ttl): ?array {
    if (!file_exists($path)) return null;

    $age = time() - filemtime($path);
    if ($age > $ttl) return null;

    $raw = file_get_contents($path);
    if (!$raw) return null;

    return json_decode($raw, true);
}

function cacheSet(string $path, array $data): void {
    file_put_contents(
        $path,
        json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
    );
}

/* ---------------- HTTP CLIENT (ROBUST) ---------------- */
function httpGetJson(string $url): ?array
{
    return httpGet($url, [
        "Accept: application/json",
        "source: okala",
    ]);
}
function httpGet(string $url, array $headers = []): ?array
{
    $attempt = 0;

    while ($attempt < MAX_RETRIES) {
        $attempt++;

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => HTTP_TIMEOUT,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response && $httpCode === 200) {
            return json_decode($response, true);
        }

        usleep(RETRY_BASE_DELAY_MS * 1000 * $attempt);
    }

    return null;
}

/* ---------------- API ---------------- */
function fetchRootCategories(array $storeIds)
{
    $query = http_build_query(
        array_map(fn($id) => ["storeIds" => $id], $storeIds)
    );

    $url = "https://apigateway.okala.com/api/Unicorn/v1/Category/GetRootCategories?" . $query;

    return httpGetJson($url);
}

function fetchStoreCategory(string $slug, int $storeId, int $pcId): ?array
{
    $url = "https://apigateway.okala.com/api/unicorn/v2/products/store/{$storeId}?pC_Id={$pcId}&slug={$slug}";

    return httpGetJson($url);
}

function fetchNearby(string $slug): ?array
{
    $url = "https://apigateway.okala.com/api/unicorn/v2/products/nearby"
        . "?slug={$slug}&lat=" . LAT . "&lon=" . LON;

    return httpGetJson($url);
}

function fetchPdp(int $storeId, int $productId): ?array
{
    $url = "https://apigateway.okala.com/api/Unicorn/v1/catalog/pdp"
        . "?sId={$storeId}&pId={$productId}";

    return httpGetJson($url);
}

/* ---------------- CORE PIPELINE ---------------- */
$seen = [];
$final = [];

foreach ($CATEGORIES as [$category_slug, $category_id]) {
    logMsg("Category: $category_slug");

    $nearbyKey = shaKey($category_slug . LAT . LON);
    $nearbyFile = cachePath("nearby", $nearbyKey);

    $json = cacheGet($nearbyFile, CACHE_TTL_NEARBY);

    if (!$json) {
        $json = fetchNearby($category_slug);

        if ($json) {
            cacheSet($nearbyFile, $json);
        }
    }

    if (!$json || empty($json['data'])) {
        continue;
    }

    foreach ($json['data'] as $block) {
        $store = $block['store'] ?? null;
        if (!$store) continue;

        $storeId = (int)$store['storeId'];

        foreach (($block['products'] ?? []) as $p) {
            $masterId = $p['masterProductId'] ?? null;
            if (!$masterId || isset($seen[$masterId])) {
                continue;
            }

            $seen[$masterId] = true;

            $pdpKey = shaKey($storeId . "_" . $p['id']);
            $pdpFile = cachePath("pdp", $pdpKey);

            $pdp = cacheGet($pdpFile, CACHE_TTL_PDP);
            if (!$pdp) {
                $pdp = fetchPdp($storeId, (int)$p['id']);
                if ($pdp) {
                    cacheSet($pdpFile, $pdp);
                }
            }

            $final[$pdp["id"]] = $pdp;
        }
    }

    $cacheKey = shaKey("store_{$storeId}_{$category_slug}");
    $cacheFile = cachePath("storecat", $cacheKey);

    $data = cacheGet($cacheFile, CACHE_TTL_STORECAT);
    if (!$data) {
        $data = fetchStoreCategory($category_slug, $storeId, $category_id);

        if ($data) {
            cacheSet($cacheFile, $data);
        }
    }

    foreach ($data["data"] ?? [] as $p) {
        $storeId = $p["storeId"];
        $pdpKey = shaKey($storeId . "_" . $p['id']);
        $pdpFile = cachePath("pdp", $pdpKey);

        $pdp = cacheGet($pdpFile, CACHE_TTL_PDP);
        if (!$pdp) {
            $pdp = fetchPdp($storeId, (int)$p['id']);
            if ($pdp) {
                cacheSet($pdpFile, $pdp);
            }
        }

        $final[$pdp["id"]] = $pdp;
    }

    usleep(200000);
}

/* ---------------- PRODUCT OUTPUT ---------------- */
file_put_contents(
    PRODUCT_OUTPUT_FILE,
    json_encode($final, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
);

logMsg("DONE: " . count($final) . " products exported.");

/* ======================================================
 * PHASE 3: ROOT CATEGORY DISCOVERY
 * ====================================================== */
logMsg("Fetching root categories...");
$rootCacheKey = shaKey("root_categories");
$rootCacheFile = cachePath("rootcats", $rootCacheKey);

$rootJson = cacheGet($rootCacheFile, 86400);
if (!$rootJson) {
    $url = "https://apigateway.okala.com/api/Unicorn/v1/Category/GetRootCategories?"
        . buildStoreIdsQuery($STORES);
    $rootJson = httpGetJson($url);
    if ($rootJson) {
        cacheSet($rootCacheFile, $rootJson);
    }
}

$rootCategories = $rootJson['entities'] ?? [];
$discoveredCategories = [];

foreach ($rootCategories as $cat) {
    $rootId = (int)$cat['id'];
    $latin = $cat['latinName'] ?? null;

    logMsg("Root: {$latin} ({$rootId})");

    $subCacheKey = shaKey("sub_categories_" . $rootId);
    $subCacheFile = cachePath("subcategories", $subCacheKey);

    $children = cacheGet($subCacheFile, 86400);
    if (!$children) {
        $url = "https://apigateway.okala.com/api/azarakhsh/v1/Category/GetChildrenByCategorySlug?". buildStoreIdsQuery($STORES) ."&slug={$latin}&activeParenLegacyId={$rootId}";
        $children = httpGetJson($url);
        if ($children) {
            cacheSet($subCacheFile, $children);
        }
    }

    $discoveredCategories[] = [
        "rootId" => $rootId,
        "slug" => $latin,
        "name" => $cat['name'] ?? null,
        "children" => $children,
    ];
}

logMsg("Discovered " . count($discoveredCategories) . " root categories");

/* ---------------- PRODUCT OUTPUT ---------------- */
file_put_contents(
    CATEGORY_OUTPUT_FILE,
    json_encode($discoveredCategories, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
);

logMsg("DONE: " . count($discoveredCategories) . " categories exported.");
