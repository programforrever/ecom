<?php

// Test the WebP optimization route
// Create a temporary route to test
Route::post('/test-webp-optimize', [\App\Http\Controllers\ImageOptimizationController::class, 'bulkOptimizeProductImages']);
