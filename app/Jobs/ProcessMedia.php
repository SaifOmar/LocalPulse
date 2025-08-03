<?php

namespace App\Jobs;

use App\Media\MediaDispatcher;
use App\Models\Account;
use App\Models\Image;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class ProcessMedia implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    // should also take a media provider to store the file
    public function __construct(public File $file, public string $newPath, private Account $account)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // TODO: hanle other media types
        $fileName = new MediaDispatcher($this->file)->handle($this->newPath);
        Image::create([
            'url' => Storage::disk('local')->url($fileName),
            'path' => $fileName,
            'account_id' => $this->account->id,
        ]);
    }
}
