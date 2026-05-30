<?php
const CONFIG_FILE = "products.json";
const IMAGE_DIR = __DIR__ . "/images";
const MAX_IMAGE_SIZE = 10 * 1024 * 1024;
const MIN_IMAGE_SIZE = 1024;
const CURL_TIMEOUT = 20;
const CURL_CONNECT_TIMEOUT = 10;
const MAX_MEMORY_USAGE = 100 * 1024 * 1024;
const MAX_IMAGE_WIDTH = 2000;

class Logger
{
    private $stats = [
        'downloaded' => 0,
        'cached' => 0,
        'failed' => 0,
        'invalid_size' => 0,
        'skipped' => 0,
    ];
    private $failedImages = [];
    private $invalidSizeImages = [];
    private $logFile;

    public function __construct(string $logDir = __DIR__)
    {
        $this->logFile = $logDir . "/download_errors.log";
    }

    public function logDownload(string $message): void
    {
        echo "[DOWNLOAD] ⬇️  $message\n";
    }

    public function logCache(string $message): void
    {
        echo "[CACHE] ⏭️  $message\n";
        $this->stats['cached']++;
    }

    public function logSuccess(string $message): void
    {
        echo "[SUCCESS] ✅ $message\n";
        $this->stats['downloaded']++;
    }

    public function logError(string $message, ?string $reason = null): void
    {
        $msg = "[ERROR] ❌ $message";
        if ($reason) {
            $msg .= " ($reason)";
        }
        echo "$msg\n";
        $this->stats['failed']++;
    }

    public function logWarning(string $message): void
    {
        echo "[WARNING] ⚠️  $message\n";
    }

    public function logInfo(string $message): void
    {
        echo "[INFO] ℹ️  $message\n";
    }

    public function incrementSkipped(): void
    {
        $this->stats['skipped']++;
    }

    public function incrementInvalidSize(): void
    {
        $this->stats['invalid_size']++;
    }

    public function recordFailedImage(string $productName, string $url, string $reason): void
    {
        $this->failedImages[] = [
            'product' => $productName,
            'url' => $url,
            'reason' => $reason,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }

    public function recordInvalidSizeImage(string $productName, string $url, int $size): void
    {
        $this->invalidSizeImages[] = [
            'product' => $productName,
            'url' => $url,
            'size' => $size,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }

    public function printStats(): void
    {
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "📊 Download Statistics\n";
        echo str_repeat("=", 60) . "\n";
        echo "✅ Downloaded: " . $this->stats['downloaded'] . "\n";
        echo "⏭️  Cached:     " . $this->stats['cached'] . "\n";
        echo "❌ Failed:     " . $this->stats['failed'] . "\n";
        echo "⚠️  Invalid Size: " . $this->stats['invalid_size'] . "\n";
        echo "⊘  Skipped:    " . $this->stats['skipped'] . "\n";
        echo str_repeat("=", 60) . "\n";

        $this->writeErrorLog();

        if (!empty($this->failedImages) || !empty($this->invalidSizeImages)) {
            echo "\n📋 Error details saved to: {$this->logFile}\n";
        }
    }

    private function writeErrorLog(): void
    {
        if (empty($this->failedImages) && empty($this->invalidSizeImages)) {
            return;
        }

        $content = "Download Error Report\n";
        $content .= "Generated: " . date('Y-m-d H:i:s') . "\n";
        $content .= str_repeat("=", 80) . "\n\n";

        if (!empty($this->failedImages)) {
            $content .= "FAILED DOWNLOADS (" . count($this->failedImages) . ")\n";
            $content .= str_repeat("-", 80) . "\n";
            foreach ($this->failedImages as $index => $image) {
                $content .= ($index + 1) . ". Product: " . $image['product'] . "\n";
                $content .= "   URL: " . $image['url'] . "\n";
                $content .= "   Reason: " . $image['reason'] . "\n";
                $content .= "   Time: " . $image['timestamp'] . "\n\n";
            }
        }

        if (!empty($this->invalidSizeImages)) {
            $content .= "\nINVALID SIZE IMAGES (" . count($this->invalidSizeImages) . ")\n";
            $content .= str_repeat("-", 80) . "\n";
            $content .= "(Valid range: " . (MIN_IMAGE_SIZE / 1024) . " KB - " . (MAX_IMAGE_SIZE / (1024 * 1024)) . " MB)\n\n";
            foreach ($this->invalidSizeImages as $index => $image) {
                $content .= ($index + 1) . ". Product: " . $image['product'] . "\n";
                $content .= "   URL: " . $image['url'] . "\n";
                $content .= "   Size: " . round($image['size'] / 1024, 2) . " KB\n";
                $content .= "   Time: " . $image['timestamp'] . "\n\n";
            }
        }

        file_put_contents($this->logFile, $content);
    }
}

class ProgressBar
{
    private $total;
    private $current = 0;
    private $startTime;

    public function __construct(int $total)
    {
        $this->total = $total;
        $this->startTime = time();
    }

    public function advance(): void
    {
        $this->current++;
        $this->render();
    }

    private function render(): void
    {
        $percentage = ($this->current / $this->total) * 100;
        $barLength = 30;
        $filledLength = (int)(($this->current / $this->total) * $barLength);

        $bar = str_repeat("█", $filledLength) . str_repeat("░", $barLength - $filledLength);
        $elapsed = time() - $this->startTime;
        $remaining = $this->current > 0 ? (int)(($elapsed / $this->current) * ($this->total - $this->current)) : 0;

        echo "\r";
        echo sprintf("[%s] %d/%d (%.1f%%) | %ds elapsed, %ds remaining",
            $bar, $this->current, $this->total, $percentage, $elapsed, $remaining);

        if ($this->current === $this->total) {
            echo "\n";
        }
    }
}

class ImageDownloader
{
    private $logger;
    private $seenUrls = [];
    private $currentProductName = "";

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function setCurrentProduct(string $productName): void
    {
        $this->currentProductName = $productName;
    }

    public function download(string $url, string $dir): bool
    {
        if (!$url) {
            $this->logger->incrementSkipped();
            return false;
        }

        $hash = md5($url);

        if (isset($this->seenUrls[$hash])) {
            $this->logger->logInfo("URL already processed: " . substr($url, 0, 50) . "...");
            $this->logger->incrementSkipped();
            return false;
        }

        $this->seenUrls[$hash] = true;
        $filePath = "{$dir}/{$hash}.webp";

        if ($this->isValidWebp($filePath)) {
            $this->logger->logCache("Cached: {$filePath}");
            return true;
        }

        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        $tempFile = sys_get_temp_dir() . "/img_" . $hash;

        $this->logger->logDownload("Downloading: " . substr($url, 0, 60) . "...");

        if (!$this->downloadToFile($url, $tempFile)) {
            $reason = "Invalid content type or network error";
            $this->logger->logError("Download failed", $reason);
            $this->logger->recordFailedImage($this->currentProductName, $url, $reason);
            @unlink($tempFile);
            return false;
        }

        $size = @filesize($tempFile);
        if ($size === false || $size < MIN_IMAGE_SIZE || $size > MAX_IMAGE_SIZE) {
            $sizeStr = $size === false ? "unknown" : "{$size} bytes";
            $this->logger->logWarning("Invalid file size: {$sizeStr}");
            $this->logger->incrementInvalidSize();
            $this->logger->recordInvalidSizeImage($this->currentProductName, $url, $size ?: 0);
            @unlink($tempFile);
            return false;
        }

        if (!$this->convertToWebp($tempFile, $filePath)) {
            $reason = "WebP conversion failed";
            $this->logger->logError($reason);
            $this->logger->recordFailedImage($this->currentProductName, $url, $reason);
            @unlink($tempFile);
            return false;
        }

        @unlink($tempFile);
        $this->logger->logSuccess("Saved: {$filePath}");
        return true;
    }

    private function isValidWebp(string $filePath): bool
    {
        if (!file_exists($filePath)) {
            return false;
        }

        $fileSize = @filesize($filePath);
        if ($fileSize === false || $fileSize < 100) {
            return false;
        }

        $info = @getimagesize($filePath);
        return $info && ($info['mime'] === 'image/webp');
    }

    private function downloadToFile(string $url, string $targetFile): bool
    {
        $fp = @fopen($targetFile, 'w+');
        if (!$fp) {
            $this->logger->logError("Cannot open temp file for writing", $targetFile);
            return false;
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_FILE => $fp,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => CURL_TIMEOUT,
            CURLOPT_CONNECTTIMEOUT => CURL_CONNECT_TIMEOUT,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 10.0; Win64; x64)",
        ]);

        curl_exec($ch);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
        fclose($fp);

        if ($httpCode !== 200) {
            $this->logger->logWarning("HTTP {$httpCode} response code");
            return false;
        }

        if (!$contentType || strpos($contentType, "image/") !== 0) {
            $this->logger->logWarning("Invalid content type: {$contentType}");
            return false;
        }

        return true;
    }

private function resizeIfNeeded($img): array
{
    $width = imagesx($img);
    $height = imagesy($img);

    if ($width <= MAX_IMAGE_WIDTH) {
        return [$img, $width, $height];
    }

    $newWidth = MAX_IMAGE_WIDTH;
    $newHeight = (int) round(($height * $newWidth) / $width);

    $resized = imagecreatetruecolor($newWidth, $newHeight);

    // preserve transparency
    imagealphablending($resized, false);
    imagesavealpha($resized, true);

    $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
    imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $transparent);

    imagecopyresampled(
        $resized,
        $img,
        0, 0, 0, 0,
        $newWidth,
        $newHeight,
        $width,
        $height
    );

    imagedestroy($img);

    return [$resized, $newWidth, $newHeight];
}
    private function convertToWebp(string $input, string $output): bool
    {
        if (!function_exists('imagewebp')) {
            $this->logger->logWarning("GD WebP support is not available");
            return false;
        }

        $info = @getimagesize($input);
        if (!$info) {
            $this->logger->logWarning("Cannot read image info");
            return false;
        }

        [$width, $height, $type] = $info;

        $estimatedMemory = $width * $height * 6;

        if ($estimatedMemory > MAX_MEMORY_USAGE) {
            $this->logger->logWarning("Image exceeds memory threshold: {$width}x{$height}");
            return false;
        }

        $data = @file_get_contents($input);
        if ($data === false) {
            $this->logger->logWarning("Cannot read file");
            return false;
        }

        $img = @imagecreatefromstring($data);
        if (!$img) {
            $this->logger->logWarning("GD failed to decode image");

            if (class_exists('Imagick')) {
                return $this->convertWithImagick($input, $output);
            }

            return false;
        }

        $img = $this->normalizeToTrueColor($img);
        [$img, $width, $height] = $this->resizeIfNeeded($img);

        imagealphablending($img, true);
        imagesavealpha($img, true);

        $dir = dirname($output);
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }

        $result = @imagewebp($img, $output, 82);

        imagedestroy($img);

        if (!$result) {
            $this->logger->logWarning("imagewebp failed for {$input}");
            return false;
        }

        return true;
    }

    private function normalizeToTrueColor($img)
    {
        $width = imagesx($img);
        $height = imagesy($img);

        $truecolor = imagecreatetruecolor($width, $height);

        imagealphablending($truecolor, false);
        imagesavealpha($truecolor, true);

        $transparent = imagecolorallocatealpha($truecolor, 0, 0, 0, 127);
        imagefilledrectangle($truecolor, 0, 0, $width, $height, $transparent);

        imagecopy($truecolor, $img, 0, 0, 0, 0, $width, $height);
        imagedestroy($img);

        return $truecolor;
    }

    private function convertWithImagick(string $input, string $output): bool
    {
        try {
            $img = new \Imagick($input);

            $img->setImageFormat('webp');
            $img->setImageCompressionQuality(82);

            $img->writeImage($output);
            $img->clear();
            $img->destroy();

            return true;
        } catch (\Throwable $e) {
            $this->logger->logWarning("Imagick fallback failed: " . $e->getMessage());
            return false;
        }
    }
}

$logger = new Logger(__DIR__);

if (!file_exists(CONFIG_FILE)) {
    $logger->logError("Configuration file not found", CONFIG_FILE);
    die(1);
}

$logger->logInfo("Loading configuration from: " . CONFIG_FILE);
$json = file_get_contents(CONFIG_FILE);
$data = json_decode($json, true);

if (!$data || !is_array($data)) {
    $logger->logError("Invalid or empty JSON", json_last_error_msg());
    die(1);
}

$logger->logInfo("Loaded " . count($data) . " products");

if (!@mkdir(IMAGE_DIR . "/brand", 0777, true)) {
    $logger->logWarning("Could not create brand directory");
}
if (!@mkdir(IMAGE_DIR . "/product", 0777, true)) {
    $logger->logWarning("Could not create product directory");
}

$logger->logInfo("Image output directory: " . IMAGE_DIR);
echo "\n";

$totalImages = 0;
foreach ($data as $item) {
    if (!empty($item["brandImage"])) {
        $totalImages++;
    }
    $images = $item["images"] ?? [];
    $totalImages += count($images);
}

$progressBar = new ProgressBar($totalImages);
$downloader = new ImageDownloader($logger);

foreach ($data as $index => $product) {
    $productName = $product["name"] ?? "Unknown";
    $logger->logInfo("Processing product: {$productName}");
    $downloader->setCurrentProduct($productName);

    if (!empty($product["brandImage"])) {
        $downloader->download($product["brandImage"], IMAGE_DIR . "/brand");
        $progressBar->advance();
    }

    $images = $product["images"] ?? [];
    foreach ($images as $imageUrl) {
        $downloader->download($imageUrl, IMAGE_DIR . "/product");
        $progressBar->advance();
    }
}

$logger->printStats();
