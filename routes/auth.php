<?php

use App\Http\Controllers\Accounts\AccountController;
use App\Http\Controllers\Auth\LoginUserController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Support\Facades\Route;

// user prefexis
Route::post("auth/users/register", RegisterUserController::class);
Route::post("auth/users/login", LoginUserController::class);

Route::delete("auth/accounts/{account}/delete", [AccountController::class, "destroy"]);
Route::put("auth/accounts/{account}/update", [AccountController::class, "update"])->middleware("auth:sanctum");
// Route::delete("auth/users/{account}/delete", [AccountController::class, "destroy"]);




Route::get("test/auth", function () {
    dump("hello world");
    $data = request()->user()->currentAccessToken();
    $account_id = explode("-", $data->name)[2];
    dump($account_id);
    $account = Account::find($account_id);
    return response()->json(new AccountResource($account))->setStatusCode(200);
})->middleware("auth:sanctum");
// Route::get("test/auth/create-token", function () {
//     $user = User::factory()->create();
//     $account = Account::create([
//         "user_id" => $user->id,
//         'handle' => "@saifomar",
//         'password' => Hash::make('password'),
//     ]);
//     $token = $user->createToken('test-token-'. $account->id);
//     return $token->plainTextToken;
//
//
// });
