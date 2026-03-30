<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Http\Kernel::class)->handle(
    $request = \Illuminate\Http\Request::capture()
);

$settings = \DB::table('business_settings')
    ->where('key', 'LIKE', 'flash_deal_banner%')
    ->get();

echo "<pre>";
foreach($settings as $s) {
    echo $s->key . " => ";
    if(strlen($s->value) > 200) {
        echo substr($s->value, 0, 200) . "...\n";
    } else {
        echo $s->value . "\n";
    }
}
if(count($settings) == 0) {
    echo "No flash_deal_banner settings found in database\n";
}
echo "</pre>";
