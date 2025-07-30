<?php

use App\Http\Controllers\LoginUserController;
use App\Http\Controllers\RegisterUserController;
use Illuminate\Support\Facades\Route;


Route::post("users/register", RegisterUserController::class);
Route::post("users/login", LoginUserController::class);
