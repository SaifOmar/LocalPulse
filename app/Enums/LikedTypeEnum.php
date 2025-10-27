<?php

namespace App\Enums;

enum LikedTypeEnum: string
{
    case PULSE = 'pulse';
    case COMMENT = 'comment';
    case STORY = 'story';

    public static function getAll(): array
    {
        return array_values(array_filter(get_class_vars(self::class)));
    }

    public static function getAllAsString(): array
    {
        return array_map(function ($value) {
            return $value;
        }, self::getAll());
    }

}
