<?php

use App\Http\Controllers\PulseController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

// api/pulses/store
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('/pulses')->group(function () {
        Route::post('/store', [PulseController::class, 'store']);
    })->middleware('auth:sanctum');
    Route::prefix('/likes')->group(function () {
        Route::post('/store', [LikeController::class, 'store']);
        Route::delete('/{id}/delete', [LikeController::class, 'destroy']);

    });
    Route::prefix('/comments')->group(function () {
        Route::post('/store', [CommentController::class, 'store']);
        Route::put('/update', [CommentController::class, 'update']);
        Route::delete('/{id}/delete', [CommentController::class, 'destroy']);
    });
});
require __DIR__ . '/auth.php';
