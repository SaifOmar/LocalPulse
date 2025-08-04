<?php

use App\Http\Controllers\PulseController;
use Illuminate\Support\Facades\Route;

// api/pulses/store
Route::prefix('/pulses')->group(function () {
    Route::post('/store', [PulseController::class, 'store']);
});
require __DIR__ . '/auth.php';
