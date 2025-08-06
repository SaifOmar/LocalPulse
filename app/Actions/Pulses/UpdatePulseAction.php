<?php

namespace App\Actions\Pulses;

use App\Models\Pulse;
use Illuminate\Support\Arr;

class UpdatePulseAction
{
    public function update(Pulse $pulse, array $data): Pulse
    {
        $tags = Arr::pull($data, 'tags');
        $action = new CreateTagAction();
        if ($tags) {
            foreach ($tags as $tag) {
                $tag = $action->store($tag);
                if ($tag) {
                    $pulse->tags()->attach($tag);
                }
            }
        }
        $pulse->update($data);
        return $pulse;
    }
}
