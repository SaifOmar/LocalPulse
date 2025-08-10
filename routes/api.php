<?php

use App\Http\Controllers\PulseController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Models\Pulse;
use Illuminate\Support\Facades\Route;
use App\Models\Interaction;

// api/pulses/store
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('/pulses')->group(function () {
        Route::post('/', [PulseController::class, 'store'])->middleware("can:create,".  Pulse::class);
        Route::put('/{pulse}', [PulseController::class, 'update'])->middleware("can:update,pulse");
        Route::delete('/{pulse}', [PulseController::class, 'destroy'])->middleware("can:delete,pulse");
    });

    Route::prefix('/likes')->group(function () {
        Route::post('/', [LikeController::class, 'store']);
        // TODO: add policy middleware
        Route::delete('/', [LikeController::class, 'destroy']);
    });

    Route::prefix('/comments')->group(function () {
        Route::post('/', [CommentController::class, 'store']);
        // TODO: add policy middleware
        Route::put('/{comment}', [CommentController::class, 'update']);
        Route::delete('/{comment}', [CommentController::class, 'destroy']);
    });
})->middleware(['throttle:60,1']);
Route::get("/interactions", function () {
    $interactions = Interaction::all()->toArray();
    return response()->json(
        $interactions
    )
        ->setStatusCode(200);
});

Route::get("/jobs", function () {
    $interactions = DB::table('jobs')->select()->get()->toArray();
    return response()->json(
        $interactions
    )
        ->setStatusCode(200);
});
require __DIR__ . '/auth.php';
