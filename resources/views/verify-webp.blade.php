<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar WebP - Archivos</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-bottom: 10px; }
        .subtitle { color: #666; margin-bottom: 30px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px; text-align: center; }
        .stat-card.green { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stat-card.blue { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .stat-card.orange { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        .stat-card h3 { font-size: 24px; margin-bottom: 5px; }
        .stat-card p { font-size: 12px; opacity: 0.9; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #4CAF50; color: white; padding: 12px; text-align: left; }
        td { padding: 10px 12px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background: #f9f9f9; }
        .ext-jpg { color: #FF6B6B; font-weight: bold; }
        .ext-png { color: #4ECDC4; font-weight: bold; }
        .ext-webp { color: #45B7D1; font-weight: bold; background: #e8f5e9; padding: 4px 8px; border-radius: 4px; }
        .ext-gif { color: #FFA07A; font-weight: bold; }
        .error { color: #d32f2f; padding: 15px; background: #ffebee; border-left: 4px solid #d32f2f; border-radius: 4px; }
        .nav-links { margin-bottom: 20px; }
        .nav-links a { display: inline-block; margin-right: 10px; padding: 8px 16px; background: #667eea; color: white; text-decoration: none; border-radius: 4px; }
        .nav-links a:hover { background: #5568d3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🖼️ Verificación de Archivos WebP</h1>
        <p class="subtitle">Verifica qué archivos has convertido a WebP</p>
        
        <div class="nav-links">
            <a href="{{ route('verify-products-images') }}">📦 Ver Imágenes de Productos</a>
            <a href="{{ route('logs-viewer') }}">📋 Ver Logs</a>
        </div>

        @if(isset($error))
            <div class="error">❌ {{ $error }}</div>
        @else
            <div class="stats-grid">
                <div class="stat-card green">
                    <h3>{{ $stats['webp'] ?? 0 }}</h3>
                    <p>Archivos WebP</p>
                </div>
                <div class="stat-card blue">
                    <h3>{{ $stats['jpg'] + $stats['jpeg'] ?? 0 }}</h3>
                    <p>Archivos JPG/JPEG</p>
                </div>
                <div class="stat-card orange">
                    <h3>{{ $stats['png'] ?? 0 }}</h3>
                    <p>Archivos PNG</p>
                </div>
                <div class="stat-card">
                    <h3>{{ $stats['total_files'] ?? 0 }}</h3>
                    <p>Total de Archivos</p>
                </div>
                <div class="stat-card purple">
                    <h3>{{ $stats['total_size_mb'] ?? 0 }} MB</h3>
                    <p>Tamaño Total</p>
                </div>
                <div class="stat-card green">
                    <h3>{{ $stats['webp_size_mb'] ?? 0 }} MB</h3>
                    <p>Tamaño WebP</p>
                </div>
            </div>

            @if(!empty($files))
                <h2>📄 Archivos (primeros 100)</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Archivo</th>
                            <th>Tipo</th>
                            <th>Tamaño</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($files->take(100) as $file)
                            <tr>
                                <td style="word-break: break-all; font-size: 12px; font-family: monospace;">{{ $file['path'] }}</td>
                                <td><span class="ext-{{ $file['ext'] }}">{{ strtoupper($file['ext']) }}</span></td>
                                <td>{{ $file['size_kb'] }} KB</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if(count($files) > 100)
                    <p style="margin-top: 15px; color: #999;">⚠️ Mostrando 100 de {{ count($files) }} archivos</p>
                @endif
            @else
                <div class="error">⚠️ No se encontraron archivos</div>
            @endif
        @endif
    </div>
</body>
</html>
