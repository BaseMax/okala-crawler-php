<?php
$filename = "products.json";
$image_dir = __DIR__ . "/images";

define("MAX_IMAGE_SIZE", 5 * 1024 * 1024); // 5MB
define("MIN_IMAGE_SIZE", 1024); // 1KB

/* ======================================================
 * INIT
 * ====================================================== */
if (!file_exists($filename)) {
    die("products.json not found\n");
}

$data = json_decode(file_get_contents($filename), true);

if (!$data) {
    die("Invalid JSON\n");
}

@mkdir($image_dir . "/brand", 0777, true);
@mkdir($image_dir . "/product", 0777, true);

/* ======================================================
 * HTTP DOWNLOAD
 * ====================================================== */
function downloadImage($url)
{
    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => "Mozilla/5.0",
        CURLOPT_HEADER => true
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        curl_close($ch);
        return null;
    }

    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $body = substr($response, $headerSize);

    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    $size = strlen($body);

    curl_close($ch);

    if (!$contentType || strpos($contentType, "image/") !== 0) {
        return null;
    }

    if ($size < MIN_IMAGE_SIZE || $size > MAX_IMAGE_SIZE) {
        return null;
    }

    return $body;
}

/* ======================================================
 * CONVERT TO WEBP
 * ====================================================== */
function convertToWebp($imageData)
{
    $img = @imagecreatefromstring($imageData);

    if (!$img) return null;

    ob_start();
    imagewebp($img, null, 80);
    $webpData = ob_get_clean();

    imagedestroy($img);

    return $webpData ?: null;
}

/* ======================================================
 * SAVE IMAGE
 * ====================================================== */
function saveImage($dir, $url)
{
    static $seen = [];

    if (!$url) return;

    $hash = md5($url);

    if (isset($seen[$hash])) return;
    $seen[$hash] = true;

    $file = "{$dir}/{$hash}.webp";

    if (file_exists($file)) return;

    echo "Downloading: $url\n";

    $raw = downloadImage($url);

    if (!$raw) {
        echo "❌ invalid image\n";
        return;
    }

    $webp = convertToWebp($raw);

    if (!$webp) {
        echo "❌ convert failed\n";
        return;
    }

    file_put_contents($file, $webp);

    echo "✅ saved: $file\n";
}

/* ======================================================
 * MAIN LOOP
 * ====================================================== */
foreach ($data as $product_item) {
    /* -------- BRAND IMAGE -------- */
    if (!empty($product_item["brandImage"])) {
        saveImage($image_dir . "/brand", $product_item["brandImage"]);
    }

    /* -------- PRODUCT IMAGES -------- */
    $images = $product_item["images"] ?? [];

    foreach ($images as $img) {
        saveImage($image_dir . "/product", $img);
    }
}
