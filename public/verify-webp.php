<?php

// Verificar archivos WebP convertidos
// Accede desde: http://yoursite.local/verify-webp.php

$uploadsPath = __DIR__ . '/uploads';

echo "<h1>📊 Verificación de Archivos WebP</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    th { background-color: #4CAF50; color: white; }
    tr:nth-child(even) { background-color: #f2f2f2; }
    .jpg { color: #FF6B6B; }
    .png { color: #4ECDC4; }
    .webp { color: #45B7D1; font-weight: bold; }
    .gif { color: #FFA07A; }
</style>";

if (!is_dir($uploadsPath)) {
    echo "<p style='color: red;'>❌ Carpeta no encontrada: $uploadsPath</p>";
    exit;
}

echo "<p>📁 Carpeta: <strong>$uploadsPath</strong></p>";

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

// Escanear archivos recursivamente
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($uploadsPath, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
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

// Mostrar estadísticas
echo "<h2>📈 Estadísticas</h2>";
echo "<table>";
echo "<tr>";
echo "<th>Tipo</th>";
echo "<th>Cantidad</th>";
echo "</tr>";

foreach ($stats as $ext => $count) {
    if ($count > 0) {
        $color = $ext === 'webp' ? 'color: green; font-weight: bold;' : '';
        echo "<tr style='{$color}'>";
        echo "<td class='{$ext}'>" . strtoupper($ext ?? 'OTHER') . "</td>";
        echo "<td>$count</td>";
        echo "</tr>";
    }
}

echo "</table>";

echo "<p style='margin-top: 20px;'>";
echo "📊 Total de archivos: <strong>" . count($files) . "</strong><br>";
echo "💾 Tamaño total: <strong>" . round($totalSize / (1024 * 1024), 2) . " MB</strong><br>";
echo "🎯 Archivos WebP: <strong style='color: green;'>" . $stats['webp'] . "</strong> (" . round($totalWebpSize / (1024 * 1024), 2) . " MB)<br>";
echo "</p>";

// Listar archivos
echo "<h2>📋 Listado de Archivos (primeros 50)</h2>";
echo "<table>";
echo "<tr>";
echo "<th>Archivo</th>";
echo "<th>Tipo</th>";
echo "<th>Tamaño</th>";
echo "</tr>";

$count = 0;
foreach ($files as $file) {
    if ($count >= 50) break;
    
    $typeClass = $file['ext'];
    echo "<tr>";
    echo "<td>" . htmlspecialchars($file['path']) . "</td>";
    echo "<td class='{$typeClass}'>" . strtoupper($file['ext']) . "</td>";
    echo "<td>" . $file['size_kb'] . " KB</td>";
    echo "</tr>";
    $count++;
}

echo "</table>";

if (count($files) > 50) {
    echo "<p style='color: orange;'>⚠️ Mostrando 50 de " . count($files) . " archivos</p>";
}

?>
