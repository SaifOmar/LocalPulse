<?php

use App\Http\Controllers\Accounts\AccountController;
use App\Http\Controllers\Auth\LoginUserController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Helpers;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::prefix("auth")->group(function () {
    // user prefexis
    //
    Route::prefix("users")->group(function () {
        Route::post("/register", RegisterUserController::class);
        Route::post("/login", LoginUserController::class);
    });
    // user prefexis

    Route::prefix("accounts")->name('accounts.')->group(function () {
        // test
        //
        Route::post('/', [AccountController::class, 'store'])->middleware("auth:sanctum")->name('store');

        Route::get('/', [AccountController::class, "index"])->name('index');
        Route::get('/current', [AccountController::class, "current"])->middleware("auth:sanctum")->name('current');

        Route::get("/{account:id}", [AccountController::class, "show"])->name('show');
        Route::put("/{account:id}/update", [AccountController::class, "update"])->middleware("auth:sanctum");
        Route::post("/{account:id}/password/update", [ResetPasswordController::class, 'reset'])->middleware("auth:sanctum");
        Route::delete("/{account:id}/delete", [AccountController::class, "destroy"]);
        // Route::post("/{account:id}/password/update-v2", function (Request $request, Account $account) {
        //     dump($request, $account->handle);
        //     return response()->json([
        //         'message' => 'password updated successfully',
        //     ])->setStatusCode(200);
        // });
    });
});



// Route::get("auth/accounts/reset-test", function () {
//     $user = User::factory()->create();
//     $account = Account::createOrFirst([
//         'handle' => "@saifomar",
//     ], [
//         "user_id" => $user->id,
//         'handle' => "@saifomar",
//         'password' => Hash::make('password'),
//     ]);
//     $token = Helpers::createUserToken($user, $account->handle);
//     // return response()->json([
//     //     'token' => $token,
//     //     'account' => $account,
//     // ])->setStatusCode(200);
//     $action = new \App\Actions\Accounts\UserUpdatePasswordAction();
//     $reset_token = $action->sendPasswordResetLink($account);
//     return response()->json([
//         'token' => $reset_token,
//         'account' => $account,
//     ])->setStatusCode(200);
// });


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
