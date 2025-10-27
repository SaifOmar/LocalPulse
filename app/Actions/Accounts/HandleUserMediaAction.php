<?php

namespace App\Actions\Accounts;

use App\Media\MediaDispatcher;
use App\Models\Account;
use App\Models\Image;
use Illuminate\Http\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;

class HandleUserMediaAction
{
    // should make it more dynamic for now that works
    public function store(Account $account, $avatar, string $destinationPath): void
    {
        $path = $avatar->getRealPath();
        $newFile = new File($path);
        $fileName = new MediaDispatcher($newFile)->handle($destinationPath);
        $url = URL::to(Storage::disk('local')->url($fileName));
        Image::create([
            'url' => $url,
            'path' => $fileName,
            'account_id' => $account->id,
        ]);
    }
}
