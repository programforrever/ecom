<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Logs</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-bottom: 10px; }
        .subtitle { color: #666; margin-bottom: 30px; }
        .nav-links { margin-bottom: 20px; }
        .nav-links a { display: inline-block; margin-right: 10px; padding: 8px 16px; background: #667eea; color: white; text-decoration: none; border-radius: 4px; }
        .nav-links a:hover { background: #5568d3; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #4CAF50; color: white; padding: 12px; text-align: left; }
        td { padding: 10px 12px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background: #f9f9f9; }
        a { color: #2196F3; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 Visor de Logs</h1>
        <p class="subtitle">Últimas líneas de los archivos de log</p>
        
        <div class="nav-links">
            <a href="{{ route('verify-webp') }}">🖼️ Ver Archivos WebP</a>
            <a href="{{ route('verify-products-images') }}">📦 Ver Imágenes de Productos</a>
        </div>

        <h2>Archivos disponibles:</h2>
        <table>
            <thead>
                <tr>
                    <th>Nombre del Log</th>
                    <th>Tamaño</th>
                    <th>Última Modificación</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td><strong>{{ $log['name'] }}</strong></td>
                        <td>{{ round($log['size'] / 1024, 2) }} KB</td>
                        <td>{{ date('Y-m-d H:i:s', $log['modified']) }}</td>
                        <td><a href="{{ route('logs-viewer.detail', $log['name']) }}">👁️ Ver</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #999;">No hay logs</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
