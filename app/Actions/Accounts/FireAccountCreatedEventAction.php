<?php

namespace App\Actions\Accounts;

use App\Events\AccountCreatedEvent;
use App\Models\Account;
use App\Models\User;

class FireAccountCreatedEventAction
{
    // public function __invoke(Account $account, User $user): void
    // {
    //     $this->dispatch($account, $user);
    // }
    // private function dispatch(Account $account, User $user): void
    // {
    //     event(new AccountCreatedEvent($account, $user));
    // }
}
