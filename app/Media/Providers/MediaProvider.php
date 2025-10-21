<?php

namespace App\Media\Providers;


class MediaProvider
{
    public function store(string $fileName): string
    {
        switch ($fileName) {
            case 'image':
                return 'image';
            case 'video':
                return 'video';
            case 'audio':
                return 'audio';
        }
        return "hello from media provider";
    }
}
