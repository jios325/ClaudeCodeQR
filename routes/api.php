<?php

use Illuminate\Support\Facades\Route;


Route::apiResource('dynamic-q-r-codes', App\Http\Controllers\DynamicQRCodeController::class);

Route::apiResource('static-q-r-codes', App\Http\Controllers\StaticQRCodeController::class);

Route::apiResource('scan-logs', App\Http\Controllers\ScanLogController::class);
