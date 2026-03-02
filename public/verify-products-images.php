<?php

// Verificar imágenes de productos en la base de datos
// Accede desde: http://yoursite.local/verify-products-images.php

require __DIR__ . '/../bootstrap/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

// Bootstrap Laravel
$kernel->handle(\Illuminate\Http\Request::capture());

use App\Models\Product;
use Illuminate\Support\Facades\File;

$uploadsPath = public_path('uploads');

echo "<h1>🖼️ Verificación de Imágenes de Productos</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
    .container { background: white; padding: 20px; border-radius: 5px; }
    table { border-collapse: collapse; width: 100%; margin: 20px 0; }
    th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
    th { background-color: #4CAF50; color: white; }
    .exists { color: green; font-weight: bold; }
    .missing { color: red; font-weight: bold; }
    .webp-exists { background-color: #d4edda; }
    .info-box { background: #e3f2fd; padding: 15px; border-left: 4px solid #2196F3; margin: 10px 0; }
    .warning-box { background: #fff3e0; padding: 15px; border-left: 4px solid #FF9800; margin: 10px 0; }
</style>";

// Obtener productos
$products = Product::limit(10)->get();

echo "<div class='container'>";
echo "<div class='info-box'>";
echo "<strong>Total de productos en BD:</strong> " . Product::count() . "<br>";
echo "<strong>Mostrando:</strong> primeros " . $products->count() . " productos<br>";
echo "<strong>Carpeta de uploads:</strong> $uploadsPath";
echo "</div>";

if ($products->isEmpty()) {
    echo "<p style='color: red;'>⚠️ No hay productos en la base de datos</p>";
} else {
    echo "<table>";
    echo "<tr>";
    echo "<th>ID</th>";
    echo "<th>Nombre</th>";
    echo "<th>Thumbnail</th>";
    echo "<th>Existe</th>";
    echo "<th>WebP existe</th>";
    echo "<th>Tamaño JPG/PNG</th>";
    echo "</tr>";
    
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
        
        $rowClass = $webpExists ? 'webp-exists' : '';
        echo "<tr class='{$rowClass}'>";
        echo "<td>" . $product->id . "</td>";
        echo "<td>" . htmlspecialchars(substr($product->name, 0, 50)) . "</td>";
        echo "<td style='font-size: 12px; font-family: monospace; word-break: break-all;'>" . htmlspecialchars($thumbnail) . "</td>";
        echo "<td class='" . ($exists ? 'exists' : 'missing') . "'>" . ($exists ? '✅ Sí' : '❌ No') . "</td>";
        echo "<td>" . ($webpExists ? '✅ WebP' : '⚪ No') . "</td>";
        echo "<td>" . $fileSize . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
}

// Contar imágenes originales vs WebP
echo "<h2>📊 Análisis de Conversión</h2>";
$originalCount = count(File::allFiles($uploadsPath, ['jpg', 'jpeg', 'png', 'gif', 'bmp']));
$webpCount = count(glob(public_path('uploads/**/*.webp'), GLOB_RECURSIVE));

echo "<div class='info-box'>";
echo "<strong>Imágenes originales:</strong> ~" . $originalCount . "<br>";
echo "<strong>Archivos WebP:</strong> " . $webpCount . "<br>";
if ($webpCount > 0) {
    echo "<strong style='color: green;'>✅ Ya hay archivos WebP generados</strong>";
} else {
    echo "<strong style='color: orange;'>⚠️ No se encontraron archivos WebP</strong>";
}
echo "</div>";

echo "</div>";

?>
