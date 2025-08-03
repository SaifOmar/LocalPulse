<?php

namespace App\Media;

interface MediaProcessor
{
    public function process(string $disPath);
    public function getNew();
    public function save(string $path, int $quality);
}
