<?php

namespace App\Media\Processors;

use App\Media\MediaProcessor;
use Illuminate\Http\File;

class AudioProcessor implements MediaProcessor
{
    public function __construct(private File $file) {}
    public function process(): File
    {
        // $this->file->convert('png');
        return $this->file;
    }
}
