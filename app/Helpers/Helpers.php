<?php

namespace App\Helpers;

use App\Models\Account;
use App\Models\User;

class Helpers
{
    public static function createHandle(string $handle): string
    {
        $handle = strtolower(trim($handle));
        return str_starts_with($handle, '@') ? $handle : "@{$handle}";
    }
    public static function createUserToken(User $user, string $meta, $type = 'handle'): string
    {
        return $user->createToken('access-'. $type . ':' . $meta, ['*'], now()->addDays(30))->plainTextToken;
    }
    public static function getUserAuthAccount(string $token): Account
    {
        $account_handle = explode(":", $token);
        return Account::where('handle', $account_handle[1])->first();
    }
}
