<?php
ini_set('memory_limit', '1024M');
ini_set('max_execution_time', '0');

const CONFIG_FILE = "products.json";
const IMAGE_DIR = __DIR__ . "/images";
const ORIGINAL_IMAGE_DIR = __DIR__ . "/images/original";
const COMPRESSED_IMAGE_DIR = __DIR__ . "/images/compressed";
const MAX_IMAGE_SIZE = 30 * 1024 * 1024;
const MIN_IMAGE_SIZE = 1024;
const CURL_TIMEOUT = 30;
const CURL_CONNECT_TIMEOUT = 15;
const MAX_IMAGE_WIDTH = 1200;
const MAX_IMAGE_HEIGHT = 1200;
const WEBP_QUALITY = 60;

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
    
        echo "\r\033[K";
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
    private $forceReprocess = false;

    public function __construct(Logger $logger, bool $forceReprocess = false)
    {
        $this->logger = $logger;
        $this->forceReprocess = $forceReprocess;
    }

    public function setCurrentProduct(string $productName): void
    {
        $this->currentProductName = $productName;
    }

    public function download(string $url, string $type = "product"): bool
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

        $extension = $this->getFileExtension($url);

        $originalPath = ORIGINAL_IMAGE_DIR . "/{$type}/{$hash}.{$extension}";
        $compressedPath = COMPRESSED_IMAGE_DIR . "/{$type}/{$hash}.webp";

        if (!$this->forceReprocess && $this->isValidWebp($compressedPath)) {
            $this->logger->logCache("Cached: {$compressedPath}");
            return true;
        }

        if (file_exists($originalPath)) {
            @unlink($originalPath);
        }
        if (file_exists($compressedPath)) {
            @unlink($compressedPath);
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

        if (!$this->saveOriginalImage($tempFile, $originalPath)) {
            $reason = "Failed to save original image";
            $this->logger->logWarning($reason);
            @unlink($tempFile);
            return false;
        }

        if (!$this->convertToWebp($tempFile, $compressedPath)) {
            $reason = "WebP conversion failed";
            $this->logger->logError($reason);
            $this->logger->recordFailedImage($this->currentProductName, $url, $reason);
            @unlink($tempFile);
            return false;
        }

        @unlink($tempFile);
        $this->logger->logSuccess("Saved: Original ({$originalPath}) + Compressed ({$compressedPath})");
        return true;
    }

    private function getFileExtension(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH);
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        if (empty($extension) || strlen($extension) > 5) {
            return 'jpg';
        }

        return strtolower($extension);
    }

    private function saveOriginalImage(string $source, string $destination): bool
    {
        $dir = dirname($destination);
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }

        return @copy($source, $destination) !== false;
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
    
    private function convertToWebp(string $input, string $output): bool
    {
        $dir = dirname($output);
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
    
        if (extension_loaded('imagick')) {
            $result = $this->convertWithImagick($input, $output);
            if ($result) {
                return true;
            }
            $this->logger->logWarning("Imagick failed, falling back to GD");
        }
    
        if (!function_exists('imagewebp')) {
            $this->logger->logWarning("GD WebP support not available");
            return false;
        }
    
        return $this->convertWithGD($input, $output);
    }

    private function convertWithImagick(string $input, string $output): bool
    {
        try {
            $img = new \Imagick($input);
    
            $width = $img->getImageWidth();
            $height = $img->getImageHeight();
    
            if ($width > MAX_IMAGE_WIDTH || $height > MAX_IMAGE_HEIGHT) {
                $img->scaleImage(MAX_IMAGE_WIDTH, MAX_IMAGE_HEIGHT, true);
            }
    
            $this->addWhiteBackground($img);
    
            $img->stripImage();
    
            $img->setImageFormat('webp');
            $img->setImageCompressionQuality(WEBP_QUALITY);
            $img->setOption('webp:lossless', 'false');
            $img->setOption('webp:alpha-quality', '60');
    
            try {
                if ($img->getImageProfile('icc')) {
                    $img->removeImageProfile('icc');
                }
            } catch (\Exception $e) {
                $this->logger->logWarning("Could not remove ICC profile: " . $e->getMessage());
            }
    
            $img->writeImage($output);
            $img->clear();
            $img->destroy();
    
            return true;
        } catch (\Exception $e) {
            $this->logger->logWarning("Imagick conversion failed: " . $e->getMessage());
            return false;
        }
    }
    
    private function addWhiteBackground(&$img): void
    {
        try {
            $bg = new \Imagick();
            $bg->newImage($img->getImageWidth(), $img->getImageHeight(), new \ImagickPixel('white'));
            
            $img->setImageFormat('png');
            $bg->setImageFormat('png');
            
            $bg->compositeImage($img, \Imagick::COMPOSITE_OVER, 0, 0);
            $img->clear();
            $img->destroy();
            $img = $bg;
        } catch (\Exception $e) {
            $this->logger->logWarning("Background addition failed, continuing without it: " . $e->getMessage());
        }
    }

    private function convertWithGD(string $input, string $output): bool
    {
        $info = @getimagesize($input);
        if (!$info) {
            $this->logger->logWarning("Cannot read image dimensions");
            return false;
        }

        [$origWidth, $origHeight] = $info;

        $newWidth = $origWidth;
        $newHeight = $origHeight;

        if ($origWidth > MAX_IMAGE_WIDTH || $origHeight > MAX_IMAGE_HEIGHT) {
            $ratio = min(MAX_IMAGE_WIDTH / $origWidth, MAX_IMAGE_HEIGHT / $origHeight);
            $newWidth = (int)($origWidth * $ratio);
            $newHeight = (int)($origHeight * $ratio);
        }

        $data = @file_get_contents($input);
        if ($data === false) {
            $this->logger->logWarning("Cannot read file");
            return false;
        }

        $img = @imagecreatefromstring($data);
        unset($data);

        if (!$img) {
            $this->logger->logWarning("Cannot decode image");
            return false;
        }

        if ($newWidth !== $origWidth || $newHeight !== $origHeight) {
            $resized = @imagecreatetruecolor($newWidth, $newHeight);
            if (!$resized) {
                imagedestroy($img);
                $this->logger->logWarning("Cannot allocate image resource");
                return false;
            }

            imagealphablending($resized, false);
            imagesavealpha($resized, true);

            $transparent = @imagecolorallocatealpha($resized, 0, 0, 0, 127);
            @imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $transparent);

            @imagecopyresampled(
                $resized,
                $img,
                0, 0, 0, 0,
                $newWidth, $newHeight,
                $origWidth, $origHeight
            );

            imagedestroy($img);
            $img = $resized;
        }

        $whiteBg = @imagecreatetruecolor($newWidth, $newHeight);
        if (!$whiteBg) {
            imagedestroy($img);
            $this->logger->logWarning("Cannot create background image");
            return false;
        }

        $white = @imagecolorallocate($whiteBg, 255, 255, 255);
        @imagefilledrectangle($whiteBg, 0, 0, $newWidth, $newHeight, $white);

        imagealphablending($whiteBg, true);
        @imagecopy($whiteBg, $img, 0, 0, 0, 0, $newWidth, $newHeight);
        imagedestroy($img);

        $img = $whiteBg;

        @imageinterlace($img, 1);

        $result = @imagewebp($img, $output, WEBP_QUALITY);
        imagedestroy($img);

        if (!$result) {
            $this->logger->logWarning("Failed to save WebP");
            return false;
        }

        return true;
    }
}

$logger = new Logger(__DIR__);

$forceReprocess = isset($argv) ? (in_array('--force', $argv) || in_array('-f', $argv) ) : true;

if ($forceReprocess) {
    $logger->logInfo("🔄 Force reprocessing all images (ignoring cache)");
    echo "\n";
}

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

@mkdir(ORIGINAL_IMAGE_DIR . "/brand", 0777, true);
@mkdir(ORIGINAL_IMAGE_DIR . "/product", 0777, true);
@mkdir(COMPRESSED_IMAGE_DIR . "/brand", 0777, true);
@mkdir(COMPRESSED_IMAGE_DIR . "/product", 0777, true);

$logger->logInfo("Original images directory: " . ORIGINAL_IMAGE_DIR);
$logger->logInfo("Compressed images directory: " . COMPRESSED_IMAGE_DIR);
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
$downloader = new ImageDownloader($logger, $forceReprocess);

foreach ($data as $index => $product) {
    $productName = $product["name"] ?? "Unknown";
    $logger->logInfo("Processing product: {$productName}");
    $downloader->setCurrentProduct($productName);

    if (!empty($product["brandImage"])) {
        $downloader->download($product["brandImage"], "brand");
        $progressBar->advance();
    }

    $images = $product["images"] ?? [];
    foreach ($images as $imageUrl) {
        $downloader->download($imageUrl, "product");
        $progressBar->advance();
    }
}

$logger->printStats();
