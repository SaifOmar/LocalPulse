<?php

namespace App\Actions\Accounts;

use App\Helpers\Helpers;
use App\Models\Account;
use Illuminate\Support\Arr;

class UpdateUserAccountAction
{
    public function updateUserDisplayData(Account $account, array $data): Account
    {
        $data = Arr::only($data, ['first_name', 'last_name', 'email']);
        $account->user->update($data);
        return $account;
    }
    public function updateUserAccount(Account $account, array $data): Account
    {
        $data = Arr::only($data, ['gender', 'avatar', 'handle', 'bio']);
        $account->update([
            ...$data,
            ...isset($data['handle']) ? ['handle' => Helpers::createHandle($data['handle'])] : []
        ]);
        return $account;
    }
    public function updateMixed(Account $account, array $data): Account
    {
        return $this->updateUserDisplayData($this->updateUserAccount($account, $data), $data);
    }
}
