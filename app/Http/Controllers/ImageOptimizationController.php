<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use File;
use Log;

class ImageOptimizationController extends Controller
{
    /**
     * Show WebP conversion page
     */
    public function show()
    {
        // The 'admin' middleware on the route already verifies access
        $uploadsPath = public_path('uploads');
        $imageCount = $this->countImages($uploadsPath);
        $webpCount = $this->countImages($uploadsPath, 'webp');
        
        return view('backend.image_optimization', compact('imageCount', 'webpCount'));
    }
    
    /**
     * Convert images to WebP using GD Library
     */
    private function convertToWebP($sourcePath, $destinationPath)
    {
        $extension = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
        
        // Load image based on extension
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
            case 'bmp':
                $image = @imagecreatefrombmp($sourcePath);
                break;
            default:
                return false;
        }
        
        if (!$image) {
            return false;
        }
        
        // Save as WebP with quality 85
        $result = imagewebp($image, $destinationPath, 85);
        imagedestroy($image);
        
        return $result;
    }
    
    /**
     * Convert all images to WebP
     */
    public function convert(Request $request)
    {
        // The 'admin' middleware on the route already verifies access
        
        $keepOriginal = $request->input('keep_original', false);
        $uploadsPath = public_path('uploads');
        
        $stats = [
            'converted' => 0,
            'skipped' => 0,
            'errors' => 0,
            'space_saved' => 0,
            'messages' => []
        ];
        
        try {
            // Check if GD is available
            if (!extension_loaded('gd')) {
                throw new \Exception('GD library is not enabled on this server');
            }
            
            // Get all image files
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
            $files = File::allFiles($uploadsPath);
            
            foreach ($files as $file) {
                $extension = strtolower($file->getExtension());
                
                // Skip if not an image format or already WebP
                if (!in_array($extension, $imageExtensions) || $extension === 'webp') {
                    $stats['skipped']++;
                    continue;
                }
                
                try {
                    $filePath = $file->getPathname();
                    $originalSize = filesize($filePath);
                    
                    // Generate WebP filename
                    $webpPath = pathinfo($filePath, PATHINFO_DIRNAME) . '/' . 
                               pathinfo($filePath, PATHINFO_FILENAME) . '.webp';
                    
                    // Skip if WebP already exists
                    if (file_exists($webpPath)) {
                        $stats['skipped']++;
                        continue;
                    }
                    
                    // Convert to WebP
                    if (!$this->convertToWebP($filePath, $webpPath)) {
                        $stats['errors']++;
                        continue;
                    }
                    
                    if (!file_exists($webpPath)) {
                        $stats['errors']++;
                        continue;
                    }
                    
                    $newSize = filesize($webpPath);
                    $spaceSaved = $originalSize - $newSize;
                    if ($spaceSaved > 0) {
                        $stats['space_saved'] += $spaceSaved;
                    }
                    
                    // Delete original if not keeping
                    if (!$keepOriginal) {
                        File::delete($filePath);
                    }
                    
                    $stats['converted']++;
                    
                } catch (\Exception $e) {
                    $stats['errors']++;
                    $stats['messages'][] = "Error: " . $e->getMessage();
                }
            }
            
            $stats['space_saved_mb'] = round($stats['space_saved'] / (1024 * 1024), 2);
            $stats['success'] = true;
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
        
        return response()->json($stats);
    }
    
    /**
     * Bulk optimize images for selected products
     */
    public function bulkOptimizeProductImages(Request $request)
    {
        // The 'admin' middleware on the route already verifies access
        \Log::info('Bulk optimize request received', [
            'user_id' => auth()->id(),
            'ids' => $request->input('ids')
        ]);
        
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'error' => 'No products selected'
            ]);
        }
        
        // Check if GD is available
        if (!extension_loaded('gd')) {
            return response()->json([
                'success' => false,
                'error' => 'GD library is not enabled on this server. Please contact your hosting provider.'
            ]);
        }
        
        $stats = [
            'converted' => 0,
            'skipped' => 0,
            'errors' => 0,
            'space_saved' => 0
        ];
        
        try {
            // Get all products
            $products = Product::whereIn('id', $ids)->get();
            
            if ($products->isEmpty()) {
                \Log::warning('No products found for ids', ['ids' => $ids]);
            }
            
            \Log::info('Processing ' . $products->count() . ' products');
            
            foreach ($products as $product) {
                // Optimize thumbnail
                if (!empty($product->thumbnail_img)) {
                    \Log::debug('Processing thumbnail: ' . $product->thumbnail_img);
                    $this->optimizeImage($product->thumbnail_img, $stats);
                }
                
                // Try to optimize from stocks if they have images
                if (method_exists($product, 'stocks')) {
                    foreach ($product->stocks as $stock) {
                        if (isset($stock->image) && !empty($stock->image)) {
                            \Log::debug('Processing stock image: ' . $stock->image);
                            $this->optimizeImage($stock->image, $stats);
                        }
                    }
                }
            }
            
            $stats['space_saved_mb'] = round($stats['space_saved'] / (1024 * 1024), 2);
            $stats['success'] = true;
            
            \Log::info('Bulk optimization complete', $stats);
            
        } catch (\Exception $e) {
            \Log::error('Image optimization error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
        
        return response()->json($stats);
    }
    
    /**
     * Optimize a single image
     */
    private function optimizeImage($imagePath, &$stats)
    {
        try {
            if (empty($imagePath)) {
                $stats['skipped']++;
                \Log::info('Image skipped - empty path');
                return;
            }
            
            // Get the full file path
            $fullPath = public_path($imagePath);
            \Log::info('Processing image', ['path' => $imagePath, 'fullPath' => $fullPath]);
            
            if (!file_exists($fullPath)) {
                $stats['skipped']++;
                \Log::warning('File not found', ['path' => $fullPath]);
                return;
            }
            
            $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
            
            // Skip if not an image or already WebP
            if (!in_array($extension, $imageExtensions)) {
                $stats['skipped']++;
                \Log::info('File skipped - not an image format', ['ext' => $extension, 'path' => $imagePath]);
                return;
            }
            
            if ($extension === 'webp') {
                $stats['skipped']++;
                \Log::info('File skipped - already WebP', ['path' => $imagePath]);
                return;
            }
            
            // Skip if WebP already exists
            $webpPath = pathinfo($fullPath, PATHINFO_DIRNAME) . '/' . 
                       pathinfo($fullPath, PATHINFO_FILENAME) . '.webp';
            
            if (file_exists($webpPath)) {
                $stats['skipped']++;
                \Log::info('File skipped - WebP already exists', ['webpPath' => $webpPath]);
                return;
            }
            
            // Check if we can read the file
            if (!is_readable($fullPath)) {
                \Log::warning('File not readable', ['path' => $fullPath]);
                $stats['skipped']++;
                return;
            }
            
            // Get original size
            $originalSize = filesize($fullPath);
            
            // Convert to WebP
            if (!$this->convertToWebP($fullPath, $webpPath)) {
                \Log::warning('Conversion failed', ['path' => $fullPath, 'webpPath' => $webpPath]);
                $stats['errors']++;
                return;
            }
            
            if (!file_exists($webpPath)) {
                \Log::warning('WebP file not saved', ['webpPath' => $webpPath]);
                $stats['errors']++;
                return;
            }
            
            $newSize = filesize($webpPath);
            $spaceSaved = $originalSize - $newSize;
            if ($spaceSaved > 0) {
                $stats['space_saved'] += $spaceSaved;
            }
            
            // Delete original
            if (file_exists($fullPath)) {
                File::delete($fullPath);
                \Log::info('Original file deleted', ['path' => $fullPath]);
            }
            
            $stats['converted']++;
            \Log::info('Image converted successfully', [
                'path' => $imagePath,
                'originalSize' => $originalSize,
                'newSize' => $newSize,
                'saved' => $spaceSaved
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error optimizing image', [
                'path' => $imagePath,
                'error' => $e->getMessage()
            ]);
            $stats['errors']++;
        }
    }
    
    /**
     * Count images by extension
     */
    private function countImages($path, $extension = null)
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
        $count = 0;
        
        try {
            if (!is_dir($path)) {
                return $count;
            }
            
            $files = File::allFiles($path);
            foreach ($files as $file) {
                $ext = strtolower($file->getExtension());
                if (in_array($ext, $imageExtensions)) {
                    if ($extension === null || $ext === $extension) {
                        $count++;
                    }
                }
            }
        } catch (\Exception $e) {
            // Silent fail
        }
        
        return $count;
    }
}
