<?php

namespace App\Actions\Pulses;

use App\Actions\Pulses\CreateTagAction;
use App\Models\Account;
use App\Models\Image;
use App\Models\Pulse;
use App\Models\Video;
use Arr;
use Illuminate\Http\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use App\Media\MediaDispatcher;

class CreatePulseAction
{
    public function store(Account $account, array $data): Pulse
    {
        $media = Arr::pull($data, 'media');
        $type = Arr::get($data, 'type');
        $tags = Arr::pull($data, 'tags');
        $destinationPath = 'public/pulses/images';

        $model = Image::class;
        if ($type == 'video') {
            $destinationPath = 'public/pulses/videos';
            $model = Video::class;
        }
        $path = $media->getRealPath();
        $newFile = new File($path);
        $fileName = new MediaDispatcher($newFile)->handle($destinationPath);
        $url = URL::to(Storage::disk('local')->url($fileName));
        $model::create([
            'url' => $url,
            'path' => $fileName,
            'account_id' => $account->id,
        ]);
        $pulse = Pulse::create([
            'account_id' => $account->id,
            'caption' => $data['caption'],
            'type' => $data['type'],
            'url' => $url,
        ]);
        $action = new CreateTagAction();
        if ($tags) {
            foreach ($tags as $tag) {
                $tag = $action->store($tag);
                $pulse->tags()->attach($tag);
            }
        }
        return $pulse;
    }
}
