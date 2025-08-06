<?php

use App\Models\Account;
use App\Helpers\Helpers;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test("can get user account with token", function () {
    $user = User::factory()->create();
    $account = Account::create([
        "user_id" => $user->id,
        'handle' => "@saifomar",
        'password' => Hash::make('password'),
    ]);
    $token = Helpers::createUserToken($user, $account->handle);
    $newToken = $user->tokens()->first();
    $newAccount = Helpers::getUserAuthAccount($newToken->name);
    expect($newAccount->id)->toBe($account->id);
}) ;
