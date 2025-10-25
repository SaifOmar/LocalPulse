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
    public static function createUserToken(User $user, string $meta, $type = 'handle', $expr = null): string
    {
        return $user->createToken('access-'. $type . ':' . $meta, ['*'], $expr ??  now()->addDays(30))->plainTextToken;
    }
    public static function getUserAuthAccount(string $token): ?Account
    {
        $account_handle = explode(":", $token);
        return Account::where('handle', $account_handle[1])->first();
    }
    public static function getTypeId(string $type): string
    {
        return match ($type) {
            'pulse_like' => "pulse_id",
            'comment_like' => "comment_id",
            'story_like' => "story_id",
            default => "pulse_id",
        };
    }
    public static function generateInteractionData($account, $pulse, $type, $meta = null): array
    {

        return  [
             'account_id' => $account->id,
             'pulse_id' => $pulse,
             'type' => $type,
             'delta' => $delta ?? null,
             'meta' => $meta ?? null,
        ];
    }
}
