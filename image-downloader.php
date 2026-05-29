<?php
$filename = "products.json";
$data = json_decode($filename);
$image_dir = "images";

if (!is_dir($image_dir)) {
    mkdir($image_dir, 0777, true);
    mkdir($image_dir . "/brand", 0777, true);
    mkdir($image_dir . "/product", 0777, true);
}

foreach ($data as $product_item) {
    $brandImage = $product_item["brandImage"] ?? null;
    if ($brandImage !== null) {
        $brandImageHash = md5($brandImage);
        $brandImageData = download brandImage;
        file_put_contents($image_dir . "/brand/" . $brandImageHash, $brandImageData);
    }

    $images = $product_item["images"] ?? [];
    foreach ($images as $image_item) {
        $imageHash = md5($image_item);
        $imageData = download image_item;
        file_put_contents($image_dir . "/product/" . $imageHash, $imageData);
    }
}
