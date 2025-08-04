<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use function dump;

class PulseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        foreach ($this->tags as $tag) {
            $tags[] = TagResource::make($tag);
        }
        // dump('tags', $tags);
        return [
            'data' => [
            'id' => $this->id,
            'account_id' => $this->account_id,
            'type' => $this->type,
            'caption' => $this->caption,
            'tags' => $tags ?? null,
            'url' => $this->url,
            ]
        ];
    }
}
