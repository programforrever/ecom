<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log: {{ $filename }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', monospace; background: #1e1e1e; color: #d4d4d4; padding: 20px; }
        .container { max-width: 1400px; margin: 0 auto; background: #252526; padding: 20px; border-radius: 4px; box-shadow: 0 2px 10px rgba(0,0,0,0.3); }
        h1 { color: #4ec9b0; margin-bottom: 5px; }
        .subtitle { color: #858585; margin-bottom: 20px; font-size: 12px; }
        .nav { margin-bottom: 20px; }
        .nav a { display: inline-block; padding: 8px 16px; background: #0e639c; color: white; text-decoration: none; border-radius: 4px; margin-right: 10px; }
        .nav a:hover { background: #1177bb; }
        pre { background: #1e1e1e; padding: 15px; border-radius: 4px; overflow-x: auto; border-left: 3px solid #007acc; }
        .log-line { line-height: 1.5; margin-bottom: 3px; }
        .error { color: #f48771; }
        .warning { color: #dcdcaa; }
        .info { color: #9cdcfe; }
        .debug { color: #858585; }
        .success { color: #4ec9b0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📄 {{ $filename }}</h1>
        <p class="subtitle">Últimas 100 líneas del archivo de log</p>
        
        <div class="nav">
            <a href="{{ route('verify-webp') }}">🖼️ Ver Archivos WebP</a>
            <a href="{{ route('verify-products-images') }}">📦 Ver Imágenes</a>
            <a href="{{ route('logs-viewer') }}">📋 Todos los Logs</a>
        </div>

        <pre>@foreach($lines as $line)
@if(!empty(trim($line)))
<div class="log-line @if(strpos($line, '[ERROR]') !== false) error @elseif(strpos($line, '[WARNING]') !== false) warning @elseif(strpos($line, '[INFO]') !== false) info @elseif(strpos($line, '[DEBUG]') !== false) debug @endif">{{ htmlspecialchars($line) }}</div>
@endif
@endforeach</pre>
    </div>
</body>
</html>
