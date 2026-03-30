<?php
define('LARAVEL_START', microtime(true));

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Get settings
$settings = DB::table('business_settings')
    ->whereIn('key', [
        'flash_deal_banner_image',
        'flash_deal_banner_title',
        'flash_deal_banner_description',
        'flash_deal_banner_whatsapp_link',
        'flash_deal_banner_button_text'
    ])
    ->get();

echo "<h2>Flash Deal Banner Settings from Database:</h2>";
echo "<pre>";
foreach($settings as $s) {
    echo $s->key . " => " . substr($s->value, 0, 150) . "\n";
}
if(count($settings) == 0) {
    echo "NO SETTINGS FOUND IN DATABASE!\n";
}
echo "</pre>";

// Test get_setting function
echo "<h2>Testing get_setting() function:</h2>";
echo "<pre>";
echo "flash_deal_banner_image: " . get_setting('flash_deal_banner_image') . "\n";
echo "flash_deal_banner_title: " . get_setting('flash_deal_banner_title') . "\n";
echo "flash_deal_banner_description: " . get_setting('flash_deal_banner_description') . "\n";
echo "</pre>";
