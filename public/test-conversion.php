<?php

// Manually test the image conversion
echo "=== Testing WebP Conversion ===\n\n";

// Create test image in temp directory
$tempDir = sys_get_temp_dir();
$testImageJpg = $tempDir . '/test_image.jpg';

// Create a test JPG image
$image = imagecreatetruecolor(100, 100);
$white = imagecolorallocate($image, 255, 255, 255);
$blue = imagecolorallocate($image, 0, 0, 255);
imagefill($image, 0, 0, $white);
imagefilledrectangle($image, 10, 10, 90, 90, $blue);
imagejpeg($image, $testImageJpg, 85);
imagedestroy($image);

echo "Created test image: $testImageJpg\n";
echo "File size: " . filesize($testImageJpg) . " bytes\n\n";

// Test conversion function
function convertToWebP($sourcePath, $destinationPath)
{
    $extension = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
    
    switch ($extension) {
        case 'jpg':
        case 'jpeg':
            $image = @imagecreatefromjpeg($sourcePath);
            break;
        case 'png':
            $image = @imagecreatefrompng($sourcePath);
            if ($image) {
                imagepalettetotruecolor($image);
                imagealphablending($image, false);
                imagesavealpha($image, true);
            }
            break;
        case 'gif':
            $image = @imagecreatefromgif($sourcePath);
            break;
        default:
            return false;
    }
    
    if (!$image) {
        echo "Failed to create image from: $sourcePath\n";
        return false;
    }
    
    $result = imagewebp($image, $destinationPath, 85);
    imagedestroy($image);
    
    return $result;
}

// Test conversion
$testWebp = $tempDir . '/test_image.webp';
echo "Converting to WebP...\n";

if (convertToWebP($testImageJpg, $testWebp)) {
    if (file_exists($testWebp)) {
        $originalSize = filesize($testImageJpg);
        $newSize = filesize($testWebp);
        $saved = $originalSize - $newSize;
        $percent = round(($saved / $originalSize) * 100, 1);
        
        echo "✅ Conversion successful!\n";
        echo "   Original JPG: $originalSize bytes\n";
        echo "   WebP size:    $newSize bytes\n";
        echo "   Space saved:  $saved bytes ($percent%)\n";
        
        // Cleanup
        unlink($testImageJpg);
        unlink($testWebp);
        echo "\n✅ All tests passed!\n";
    } else {
        echo "❌ WebP file was not created\n";
    }
} else {
    echo "❌ Conversion function returned false\n";
}
