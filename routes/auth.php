<?php

use App\Http\Controllers\Accounts\AccountController;
use App\Http\Controllers\Auth\LoginUserController;
use App\Http\Controllers\Auth\RegisterUserController;
use Illuminate\Support\Facades\Route;


// user prefexis
Route::post("auth/users/register", RegisterUserController::class);
Route::post("auth/users/login", LoginUserController::class);

Route::delete("auth/accounts/{account}/delete", [AccountController::class, "destroy"]);
Route::put("auth/accounts/{account}/update", [AccountController::class, "update"]);
// Route::delete("auth/users/{account}/delete", [AccountController::class, "destroy"]);
