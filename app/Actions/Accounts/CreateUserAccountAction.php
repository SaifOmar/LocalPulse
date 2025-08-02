<?php

namespace App\Actions\Accounts;

use App\Helpers\Helpers;
use App\Models\Account;
use Illuminate\Support\Arr;
use App\Models\User;
use function array_merge;

class CreateUserAccountAction
{
    public function first(User $user, array $data): Account
    {;
        return $this->createAccount($user, array_merge(['first' => true], $data));
    }
    public function afterFirst(User $user, array $data): Account
    {
        return $this->createAccount($user, array_merge(['first' => false], $data));
    }
    private function createAccount(User $user, array $data): Account
    {
        return Account::create([
            'user_id' => $user->id,
            ...Arr::except($data, ['handle']),
            'handle' => Helpers::createHandle($data['handle'])
        ]);
    }
}
