<?php

namespace App\Actions\Accounts;

use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;

class UserUpdatePasswordAction
{
    // INFO: this weird now because the meail is on the user model not the accoutn model but the user only has one email
    // but I need to change password based on acocunt
    // which means that I have a flaw in my desing that the user passowrd is on the the user model itself when it shouldn't be
    public function sendPasswordResetLink(Account $account): string
    {
        $resetToken = Str::random(60);
        DB::table('password_reset_tokens')->updateOrInsert(['email' => $account->user->email, 'account_id' => $account->id], [
            'email' => $account->user->email,
            'account_id' => $account->id,
            'token' => $resetToken,
            'created_at' => now(),
        ]);

        Mail::to($account->user)->send(new \App\Mail\ResetPasswordLink($account->user->email, $resetToken));

        return $resetToken;
    }
    public function updatePassword(Account $account, string $token, string $password): Account
    {
        // chekc if the token is valid
        $token = DB::table('password_reset_tokens')->where([
            'email' => $account->user->email,
            'token' => $token,
            'account_id' => $account->id,
        ])->first();
        if (!$token) {
            throw new \Exception("Invalid token");
        }
        $account->update([
            'password' => Hash::make($password)
        ]);
        return $account;
    }
}
