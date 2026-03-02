<?php

// Test GD Library availability and WebP support
echo "=== GD Library Test ===\n\n";

if (!extension_loaded('gd')) {
    echo "❌ GD library is NOT loaded!\n";
    exit(1);
}

echo "✅ GD library is loaded\n";

// Check WebP support
if (!function_exists('imagewebp')) {
    echo "❌ WebP support is NOT available\n";
    echo "   Install php-gd with WebP support\n";
    exit(1);
}

echo "✅ WebP support is available\n";

// Test basic image functions
$requiredFunctions = [
    'imagecreatefromjpeg',
    'imagecreatefrompng',
    'imagecreatefromgif',
    'imagewebp',
    'imagedestroy'
];

echo "\n=== Required Functions ===\n";
foreach ($requiredFunctions as $func) {
    if (function_exists($func)) {
        echo "✅ $func\n";
    } else {
        echo "❌ $func NOT FOUND\n";
    }
}

// Test creating a simple WebP
echo "\n=== WebP Creation Test ===\n";

// Create a 10x10 image
$img = imagecreatetruecolor(10, 10);
$red = imagecolorallocate($img, 255, 0, 0);
imagefill($img, 0, 0, $red);

$testFile = sys_get_temp_dir() . '/test.webp';

if (imagewebp($img, $testFile, 85)) {
    if (file_exists($testFile)) {
        $size = filesize($testFile);
        echo "✅ Successfully created WebP file\n";
        echo "   File: $testFile\n";
        echo "   Size: $size bytes\n";
        unlink($testFile);
    } else {
        echo "❌ WebP file was not created\n";
    }
} else {
    echo "❌ imagewebp() failed\n";
}

imagedestroy($img);

echo "\n=== All Tests Complete ===\n";
