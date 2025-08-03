<?php

namespace App\Media\Detectors;

use Illuminate\Http\File;

class FileTypeDetector
{
    private string $mime;
    private string $type;
    private string $extension;
    public function __construct(public File $file)
    {
    }
    public function detectMime(): self
    {
        if (isset($this->mime)) {
            return $this;
        }
        $this->mime  = $this->file->getMimeType();
        return $this;
    }
    public function detectExtension(): self
    {
        $this->detectMime();
        $this->extension = $this->file->extension();
        return $this;
    }
    public function detectType(): self
    {
        $this->detectMime();
        $str = explode('/', $this->mime);
        $this->type = $str[0];
        return $this;
    }
    public function getType(): string
    {
        return $this->type;
    }
    public function getExtension(): string
    {
        return $this->extension;
    }
}
