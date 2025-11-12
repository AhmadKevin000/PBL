<?php
// Simple one-off script to resize and crop assets/img/map-gedung.png to 640x480 JPG (4:3)
// Usage: php tools/resize_map.php

$srcPath = __DIR__ . '/../assets/img/map-gedung.png';
$outPath = __DIR__ . '/../assets/img/map-gedung-640x480.jpg';
$targetW = 640;
$targetH = 480; // 4:3
$quality = 85;

if (!file_exists($srcPath)) {
  fwrite(STDERR, "Source image not found: $srcPath\n");
  exit(1);
}

$src = imagecreatefrompng($srcPath);
if (!$src) {
  fwrite(STDERR, "Failed to load PNG: $srcPath\n");
  exit(1);
}

$srcW = imagesx($src);
$srcH = imagesy($src);

// Compute crop to 4:3 centered
$targetRatio = $targetW / $targetH; // 1.3333
$srcRatio = $srcW / $srcH;

if ($srcRatio > $targetRatio) {
  // Source is wider than 4:3 -> crop width
  $newW = (int) round($srcH * $targetRatio);
  $newH = $srcH;
  $srcX = (int) round(($srcW - $newW) / 2);
  $srcY = 0;
} else {
  // Source is taller/narrower -> crop height
  $newW = $srcW;
  $newH = (int) round($srcW / $targetRatio);
  $srcX = 0;
  $srcY = (int) round(($srcH - $newH) / 2);
}

$dst = imagecreatetruecolor($targetW, $targetH);
// White background for JPG (in case of transparency)
$white = imagecolorallocate($dst, 255, 255, 255);
imagefill($dst, 0, 0, $white);

$ok = imagecopyresampled(
  $dst, $src,
  0, 0, // dst x,y
  $srcX, $srcY, // src x,y (crop start)
  $targetW, $targetH, // dst w,h
  $newW, $newH // src w,h (crop size)
);

if (!$ok) {
  imagedestroy($src);
  imagedestroy($dst);
  fwrite(STDERR, "Resample failed\n");
  exit(1);
}

if (!imagejpeg($dst, $outPath, $quality)) {
  imagedestroy($src);
  imagedestroy($dst);
  fwrite(STDERR, "Failed to write output: $outPath\n");
  exit(1);
}

imagedestroy($src);
imagedestroy($dst);

echo "Wrote: $outPath\n";
