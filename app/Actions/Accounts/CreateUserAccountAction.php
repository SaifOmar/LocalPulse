<?php

namespace App\Actions\Accounts;

use App\Helpers\Helpers;
use App\Jobs\ProcessMedia;
use App\Media\MediaDispatcher;
use App\Models\Account;
use App\Models\Image;
use Illuminate\Support\Arr;
use App\Models\User;
use Illuminate\Http\File;
use Illuminate\Support\Facades\File as FacadesFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;

use function array_merge;
use function array_pop;

class CreateUserAccountAction
{
    public function first(User $user, array $data): Account
    {
        return $this->createAccount($user, array_merge(['first' => true], $data));
    }
    public function afterFirst(User $user, array $data): Account
    {
        return $this->createAccount($user, array_merge(['first' => false], $data));
    }

    private function createAccount(User $user, array $data): Account
    {
        $avatar = null;

        // Extract avatar if present

        $avatar = Arr::pull($data, 'avatar'); // get & remove in one step
        // Create account
        $account = Account::create([
            'user_id' => $user->id,
            ...Arr::except($data, ['handle']),
            'handle' => Helpers::createHandle($data['handle'])
        ]);

        if ($avatar) {
            $path = $avatar->getRealPath(); // gets full path to the file
            $newFile = new File($path);


            $destinationPath = 'avatars/' . $account->id; // e.g., local/app/avatars/{id}
            $fileName = new MediaDispatcher($newFile)->handle($destinationPath);
            $url = URL::to(Storage::disk('local')->url($fileName));

            Image::create([
                'url' => $url,
                'path' => $fileName,
                'account_id' => $account->id,
            ]);

            // $destinationPath = 'avatars/' . $account->id; // e.g., local/app/avatars/{id}
            // ProcessMedia::dispatch($newFile, $destinationPath, $account);
        }

        return $account;
    }
}
