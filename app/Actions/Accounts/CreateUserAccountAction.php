<?php

namespace App\Actions\Accounts;

use App\Helpers\Helpers;
use App\Models\Account;
use Illuminate\Support\Arr;
use App\Models\User;
use Illuminate\Support\Facades\File;
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
        // this is just here for testing purposes
        if (isset($data['avatar'])) {
            $avatar = $data['avatar'];
            // dd(File::get($avatar));
            $dirname = File::dirname($avatar);
            $fileCreatedName =  File::name($avatar);
            // dd(File::basename($avatar));
            $type = str_replace('image/', '', File::mimeType($avatar));
            $fileName = sprintf('%s/%s.%s', $dirname, $fileCreatedName, $type);
            $data['avatar'] = $fileName;
        }
        return Account::create([
            'user_id' => $user->id,
            ...Arr::except($data, ['handle']),
            'handle' => Helpers::createHandle($data['handle'])
        ]);
    }
}
