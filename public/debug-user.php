<?php

// Quick debug script to check your user record
// Access from browser: yoursite.local/debug-user.php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = \Illuminate\Http\Request::capture()
);

// Check current user
if (auth()->check()) {
    $user = auth()->user();
    echo "<pre>";
    echo "ID: " . $user->id . "\n";
    echo "Name: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    
    // Check all columns
    echo "\nAll user columns:\n";
    foreach ($user->getAttributes() as $key => $value) {
        echo "  $key: " . (is_array($value) ? json_encode($value) : $value) . "\n";
    }
    
    // Check roles
    echo "\nRoles:\n";
    if ($user->roles()->count() > 0) {
        foreach ($user->roles as $role) {
            echo "  - " . $role->name . "\n";
        }
    } else {
        echo "  (no roles)\n";
    }
    
    echo "</pre>";
} else {
    echo "Not authenticated";
}
