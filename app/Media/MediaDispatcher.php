<?php

namespace App\Media;

use App\Media\Detectors\FileTypeDetector;
use App\Media\Processors\AudioProcessor;
use App\Media\Processors\ImageProcessor;
use App\Media\Processors\VideoProcessorMp4;
use Illuminate\Http\File;

// interface Media // base implementation not really
// {
//     public function detectMime(): self;
//     public function detectType(): self;
// }
// enum SupportedMediaTypes: string
// {
//     case IMAGE = 'image';
//     case VIDEO = 'video';
//     case AUDIO = 'audio';
// }
// enum SupportedMediaExtensions: string
// {
//     case PNG = 'png';
//     case JPG = 'jpg';
//     case JPEG = 'jpeg';
// }

class MediaDispatcher
{
    public function __construct(private File $file)
    {
    }
    // all pathes here should return a string that is the name to the file
    /**
     * Handles the file and returns the name of the file
     */
    public function handle(string $path)
    {
        return $this->determineFileProcessor(
            new FileTypeDetector($this->file)->detectType()->detectExtension(),
            $path,
        );
    }
    private function determineFileProcessor(FileTypeDetector $detector, $path)
    {
        $type = $detector->getType();
        $extension = $detector->getExtension();

        return match ($type) {
            "image" => new ImageProcessor($this->file)->process($path),
            "video" => match ($extension) {
                "mp4" => new VideoProcessorMp4($this->file),
                default => throw new \Exception("Unsupported file type"),
            },
            "audio" => new AudioProcessor($this->file),
            default => throw new \Exception("Unsupported file type"),
        };
    }

    // just something for now
    public function store(File $file): string
    {
        $fileName = sprintf(
            "%s/%s.%s",
            $this->file->getRealPath(),
            $this->file->getBasename(),
            $this->file->extension(),
        );
        $file->move($fileName);
        return $fileName;
    }
}
