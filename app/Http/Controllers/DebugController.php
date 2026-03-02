<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\File;

class DebugController extends Controller
{
    public function verifyWebp()
    {
        $uploadsPath = public_path('uploads');
        
        $stats = [
            'jpg' => 0,
            'jpeg' => 0,
            'png' => 0,
            'gif' => 0,
            'bmp' => 0,
            'webp' => 0,
            'others' => 0
        ];

        $files = [];
        $totalSize = 0;
        $totalWebpSize = 0;

        if (!is_dir($uploadsPath)) {
            return response()->json(['error' => "No existe: $uploadsPath"]);
        }

        try {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($uploadsPath, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $ext = strtolower($file->getExtension());
                    $size = $file->getSize();
                    $relativePath = substr($file->getPathname(), strlen($uploadsPath) + 1);
                    
                    $files[] = [
                        'path' => $relativePath,
                        'ext' => $ext,
                        'size' => $size,
                        'size_kb' => round($size / 1024, 2)
                    ];
                    
                    $totalSize += $size;
                    
                    if (isset($stats[$ext])) {
                        $stats[$ext]++;
                        if ($ext === 'webp') {
                            $totalWebpSize += $size;
                        }
                    } else {
                        $stats['others']++;
                    }
                }
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        usort($files, function ($a, $b) {
            return strcmp($a['path'], $b['path']);
        });

        $stats['total_size_mb'] = round($totalSize / (1024 * 1024), 2);
        $stats['webp_size_mb'] = round($totalWebpSize / (1024 * 1024), 2);
        $stats['total_files'] = count($files);

        return response()->json([
            'status' => 'success',
            'stats' => $stats,
            'files' => array_slice($files, 0, 50)
        ]);
    }

    public function verifyProductsImages()
    {
        $uploadsPath = public_path('uploads');
        $products = Product::limit(20)->get();

        $webpCount = 0;
        if (is_dir($uploadsPath)) {
            try {
                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($uploadsPath, \RecursiveDirectoryIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::SELF_FIRST
                );
                foreach ($iterator as $file) {
                    if ($file->isFile() && strtolower($file->getExtension()) === 'webp') {
                        $webpCount++;
                    }
                }
            } catch (\Exception $e) {
                // Silent fail
            }
        }

        $productImages = [];
        foreach ($products as $product) {
            $thumbnail = $product->thumbnail_img;
            $fullPath = public_path($thumbnail);
            $exists = file_exists($fullPath);
            $webpPath = '';
            $webpExists = false;
            $fileSize = 'N/A';
            
            if ($exists) {
                $fileSize = round(filesize($fullPath) / 1024, 2) . ' KB';
                $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
                if ($ext !== 'webp') {
                    $webpPath = pathinfo($fullPath, PATHINFO_DIRNAME) . '/' . 
                               pathinfo($fullPath, PATHINFO_FILENAME) . '.webp';
                    $webpExists = file_exists($webpPath);
                }
            }
            
            $productImages[] = [
                'id' => $product->id,
                'name' => substr($product->name, 0, 50),
                'thumbnail' => $thumbnail,
                'exists' => $exists,
                'webp_exists' => $webpExists,
                'file_size' => $fileSize
            ];
        }

        return response()->json([
            'status' => 'success',
            'total_products' => Product::count(),
            'webp_count' => $webpCount,
            'products' => $productImages
        ]);
    }

    public function checkUploadsTable()
    {
        // Obtener uploads relacionados con thumbnails de productos
        $uploads = \DB::table('uploads')
            ->whereIn('id', [183, 186, 188, 190, 192, 194, 196, 198, 200])
            ->get()
            ->toArray();

        if (empty($uploads)) {
            return response()->json(['error' => 'No uploads found']);
        }

        $result = [];
        foreach ($uploads as $upload) {
            $result[] = (array) $upload;
        }

        return response()->json([
            'status' => 'success',
            'uploads' => $result,
            'total' => count($result)
        ]);
    }

    public function checkAllUploads()
    {
        // Ver estructura de la tabla uploads
        $uploads = \DB::table('uploads')->limit(10)->get();
        $columns = \DB::getSchemaBuilder()->getColumnListing('uploads');

        return response()->json([
            'status' => 'success',
            'columns' => $columns,
            'sample_data' => $uploads
        ]);
    }
}
