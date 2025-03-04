<?php

use App\Http\Controllers\QRCodeRedirectController;
use Illuminate\Support\Facades\Route;

// Welcome page
Route::get('/', function () {
    return redirect()->route('filament.admin.auth.login');
});

// QR Code redirect routes
Route::get('/qr/{identifier}', [QRCodeRedirectController::class, 'dynamicRedirect'])
    ->name('redirect.dynamic');

// Filament handles authentication routes for admin access
