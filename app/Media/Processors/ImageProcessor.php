<?php

namespace App\Media\Processors;

use App\Media\MediaProcessor;
use Illuminate\Http\File;


class ImageProcessor implements MediaProcessor
{
    public function __construct(private File $file) {}
    public function process(): File
    {
        return $this->file;
    }
}
