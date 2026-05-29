# Okala Crawler PHP

A PHP crawler for [Okala](https://okala.com), the Iranian online grocery and delivery platform. Fetches products and categories from the Okala API across multiple stores and geographic locations, with built-in caching, retry logic, and a separate image downloader that converts all images to WebP format.

## Features

- Crawls products from 16+ Okala store categories
- Fetches product detail pages (PDP) per store
- Discovers root and sub-categories automatically
- File-based JSON cache with configurable TTLs (1h nearby, 24h PDP, 2.4h store-category)
- Automatic retry with exponential back-off (up to 3 attempts)
- Image downloader: downloads brand and product images, validates size, converts to WebP at 80% quality
- Deduplicates products by `masterProductId`
- Outputs `products.json` and `categories.json`

## Requirements

- PHP 8.0+
- `curl` extension
- `gd` extension (for WebP conversion)

## Usage

### Step 1 — Crawl products and categories

```bash
php crawler.php
```

Outputs:
- `products.json` — all crawled product detail records
- `categories.json` — root and sub-category tree
- `cache/` — cached API responses (auto-managed)

### Step 2 — Download images

```bash
php image-downloader.php
```

Reads `products.json` and downloads all brand and product images into:

```
images/
  brand/    # brand logos, named by md5(url).webp
  product/  # product images, named by md5(url).webp
```

Already-downloaded valid WebP files are skipped automatically.

## Configuration

Edit the constants at the top of `crawler.php` to adjust behavior:

| Constant | Default | Description |
|---|---|---|
| `LAT` / `LON` | `35.805851` / `51.431311` | Location coordinates (Tehran) |
| `CACHE_TTL_NEARBY` | `3600` | Nearby API cache TTL in seconds |
| `CACHE_TTL_PDP` | `86400` | Product detail cache TTL in seconds |
| `CACHE_TTL_STORECAT` | `8640` | Store-category cache TTL in seconds |
| `HTTP_TIMEOUT` | `25` | Request timeout in seconds |
| `MAX_RETRIES` | `3` | Max retry attempts per request |
| `RETRY_BASE_DELAY_MS` | `400` | Base delay (ms) between retries |

Image size limits are configured in `image-downloader.php`:

| Constant | Default | Description |
|---|---|---|
| `MAX_IMAGE_SIZE` | `5MB` | Maximum accepted image file size |
| `MIN_IMAGE_SIZE` | `1KB` | Minimum accepted image file size |

## Output Format

`products.json` is a JSON object keyed by product ID, each value being the raw PDP response from the Okala API.

`categories.json` is a JSON array of root categories, each containing:

```json
{
  "rootId": 1467,
  "slug": "kalabarg",
  "name": "...",
  "children": { ... }
}
```

## License

MIT License

Copyright (c) 2026 Seyyed Ali Mohammadiyeh (Max Base)

See [LICENSE](LICENSE) for full text.
