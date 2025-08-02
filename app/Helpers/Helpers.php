<?php

namespace App\Helpers;


class Helpers
{
    public static function createHandle(string $handle): string
    {
        $handle = strtolower(trim($handle));
        return str_starts_with($handle, '@') ? $handle : "@{$handle}";
    }
}
