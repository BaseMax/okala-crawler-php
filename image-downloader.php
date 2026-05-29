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
function isValidWebp($file)
{
    if (!file_exists($file)) return false;

    if (filesize($file) < 100) return false; // too small = broken

    $info = @getimagesize($file);

    if (!$info) return false;

    return $info['mime'] === 'image/webp';
}

function saveImage($dir, $url)
{
    static $seen = [];

    if (!$url) return;

    $hash = md5($url);
    $file = "{$dir}/{$hash}.webp";

    if (isset($seen[$hash])) return;
    $seen[$hash] = true;

    if (isValidWebp($file)) {
        echo "⏭️ skip (cached): $file\n";
        return;
    }

    if (file_exists($file)) {
        unlink($file);
    }

    $tmp = sys_get_temp_dir() . "/img_" . $hash;

    echo "⬇️ downloading: $url\n";

    if (!downloadImageToFile($url, $tmp)) {
        echo "❌ download failed\n";
        return;
    }

    $size = filesize($tmp);
    if ($size < MIN_IMAGE_SIZE || $size > MAX_IMAGE_SIZE) {
        echo "❌ invalid size\n";
        unlink($tmp);
        return;
    }

    if (!convertFileToWebp($tmp, $file)) {
        echo "❌ convert failed\n";
        unlink($tmp);
        return;
    }

    unlink($tmp);

    echo "✅ saved: $file\n";
}

function downloadImageToFile($url, $tmpFile)
{
    $fp = fopen($tmpFile, 'w+');

    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_FILE => $fp,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => "Mozilla/5.0",
    ]);

    curl_exec($ch);

    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

    curl_close($ch);
    fclose($fp);

    if (!$contentType || strpos($contentType, "image/") !== 0) {
        unlink($tmpFile);
        return false;
    }

    return true;
}

/* ======================================================
 * CONVERT TO WEBP
 * ====================================================== */
function convertToWebp($imageData)
{
    $info = @getimagesizefromstring($imageData);

    if (!$info) return null;

    $width  = $info[0];
    $height = $info[1];

    $estimatedMemory = $width * $height * 4;
    if ($estimatedMemory > 100 * 1024 * 1024) {
        echo "❌ too large (dimensions)\n";
        return null;
    }

    $img = @imagecreatefromstring($imageData);
    if (!$img) return null;

    ob_start();
    imagewebp($img, null, 80);
    $webpData = ob_get_clean();

    imagedestroy($img);

    return $webpData ?: null;
}

function convertFileToWebp($input, $output)
{
    $info = @getimagesize($input);
    if (!$info) return false;

    $width  = $info[0];
    $height = $info[1];

    $estimatedMemory = $width * $height * 4;

    if ($estimatedMemory > 50 * 1024 * 1024) {
        echo "❌ too large (dimensions)\n";
        return false;
    }

    $data = file_get_contents($input);
    if (!$data) return false;

    $img = @imagecreatefromstring($data);
    if (!$img) return false;

    if (!imageistruecolor($img)) {
        $truecolor = imagecreatetruecolor(imagesx($img), imagesy($img));

        imagealphablending($truecolor, false);
        imagesavealpha($truecolor, true);

        imagecopy($truecolor, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));

        imagedestroy($img);
        $img = $truecolor;
    }

    imagealphablending($img, true);
    imagesavealpha($img, true);

    $result = imagewebp($img, $output, 80);

    imagedestroy($img);

    return $result;
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
