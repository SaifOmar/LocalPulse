<?php

namespace App\Actions\Accounts;

use App\Models\Account;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserUpdatePasswordAction
{
    // INFO: this weird now because the meail is on the user model not the accoutn model but the user only has one email
    // but I need to change password based on acocunt
    // which means that I have a flaw in my desing that the user passowrd is on the the user model itself when it shouldn't be
    public function sendPasswordResetLink(Account $account): Account
    {
        $account->update(['password_reset_token' => Str::random(60)]);
        return $account;
    }
    public function updatePassword(Account $account, string $password): Account
    {
        $account->update([
            'password' => Hash::make($password)
        ]);
        return $account;
    }
}
