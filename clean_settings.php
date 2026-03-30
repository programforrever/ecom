<?php
require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$deleted = DB::table('business_settings')
    ->where('type', 'flash_deal_banner_whatsapp_lin')
    ->delete();

echo "Eliminados {$deleted} registros mal guardados\n";

// Verificar datos actuales
$current = DB::table('business_settings')
    ->where('type', 'like', 'flash_deal%')
    ->get();

echo "\nDatos actuales de flash_deal:\n";
foreach($current as $setting) {
    echo "- {$setting->type}: " . substr($setting->value, 0, 50) . "\n";
}
