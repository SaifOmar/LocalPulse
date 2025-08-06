<?php

namespace App\Actions\Pulses;

use App\Models\Tag;
use Illuminate\Support\Str;

class CreateTagAction
{
    public function store(string $name): ?Tag
    {
        $tag_name = $this->normalizeTag($name);
        $tag_slug = Str::slug($tag_name);
        if ($tag = Tag::where('slug', $tag_slug)->first()) {
            return null;
        }
        $tag = Tag::create([
            'name' => $name,
            'slug' => $tag_slug,
        ]);
        return $tag;
    }
    public function normalizeTag(string $tag): string
    {
        $tag = strtolower(trim($tag));
        return $tag;
    }
}
