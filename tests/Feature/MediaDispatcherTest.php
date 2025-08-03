<?php

use App\Actions\Accounts\CreateUserAccountAction;
use App\Media\MediaDispatcher;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Jobs\ProcessMedia;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);
test('Queue dispatched', function () {
    Storage::fake('local');
    Queue::fake();

    $filePath = (__DIR__ . '/../Unit/test/mrsjpg.jgp');
    $file = new File($filePath);

    $distPath = 'processed';

    $action = new CreateUserAccountAction();
    $user = User::factory()->create();
    $data = [
        'handle' => 'SaifOmar',
    ];
    $account = $action->first($user, $data);
    ProcessMedia::dispatch($file, $distPath, $account);

    Queue::assertPushed(ProcessMedia::class, function ($job) use ($file) {
        return $job->file === $file;
    });
});

test('MediaDispatcher handles file and stores it', function () {
    Storage::fake('local');

    $filePath = (__DIR__ . '/../Unit/test/mrsjpg.jgp');
    $file = new File($filePath);
    $distPath = 'processed'; // This is just a subdirectory in `storage/app`

    $dispatcher = new MediaDispatcher($file);
    $newFilename = $dispatcher->handle($distPath);

    Storage::disk('public')->assertExists("{$newFilename}");
});
