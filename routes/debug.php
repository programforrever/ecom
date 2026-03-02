<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DebugController;

// Debug routes para WebP verification
Route::get('/verify-webp', [DebugController::class, 'verifyWebp'])->name('verify-webp');
Route::get('/verify-products-images', [DebugController::class, 'verifyProductsImages'])->name('verify-products-images');
Route::get('/check-uploads-table', [DebugController::class, 'checkUploadsTable'])->name('check-uploads-table');
Route::get('/check-all-uploads', [DebugController::class, 'checkAllUploads'])->name('check-all-uploads');
