<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar WebP - Productos</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-bottom: 10px; }
        .subtitle { color: #666; margin-bottom: 30px; }
        .info-box { background: #e3f2fd; padding: 15px; border-left: 4px solid #2196F3; margin-bottom: 20px; border-radius: 4px; }
        .info-box strong { color: #1976D2; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #4CAF50; color: white; padding: 12px; text-align: left; }
        td { padding: 10px 12px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background: #f9f9f9; }
        .exists { color: green; font-weight: bold; }
        .missing { color: red; font-weight: bold; }
        .webp-row { background: #d4edda !important; }
        .error { color: #d32f2f; }
        .nav-links { margin-bottom: 20px; }
        .nav-links a { display: inline-block; margin-right: 10px; padding: 8px 16px; background: #667eea; color: white; text-decoration: none; border-radius: 4px; }
        .nav-links a:hover { background: #5568d3; }
        .monospace { font-family: 'Courier New', monospace; word-break: break-all; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📦 Verificación de Imágenes de Productos</h1>
        <p class="subtitle">Verifica qué productos tienen versiones WebP</p>
        
        <div class="nav-links">
            <a href="{{ route('verify-webp') }}">🖼️ Ver Archivos WebP</a>
            <a href="{{ route('logs-viewer') }}">📋 Ver Logs</a>
        </div>

        <div class="info-box">
            <strong>📊 Total de productos en BD:</strong> {{ count($products) }}<br>
            <strong>🎯 WebP detectados:</strong> <span style="color: green; font-weight: bold;">{{ $webpCount }}</span><br>
            <strong>📁 Carpeta:</strong> <code>{{ public_path('uploads') }}</code>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Ruta de Imagen</th>
                    <th>¿Existe?</th>
                    <th>WebP</th>
                    <th>Tamaño</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productImages as $product)
                    <tr class="{{ $product['webp_exists'] ? 'webp-row' : '' }}">
                        <td>{{ $product['id'] }}</td>
                        <td><strong>{{ substr($product['name'], 0, 40) }}</strong>{{ strlen($product['name']) > 40 ? '...' : '' }}</td>
                        <td class="monospace">{{ $product['thumbnail'] }}</td>
                        <td class="{{ $product['exists'] ? 'exists' : 'missing' }}">
                            {{ $product['exists'] ? '✅ Sí' : '❌ No' }}
                        </td>
                        <td>
                            @if($product['webp_exists'])
                                <span style="background: #4CAF50; color: white; padding: 4px 8px; border-radius: 4px;">✅ WebP</span>
                            @else
                                <span style="background: #999; color: white; padding: 4px 8px; border-radius: 4px;">⚪ No</span>
                            @endif
                        </td>
                        <td>{{ $product['file_size'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #999;">No hay productos</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
