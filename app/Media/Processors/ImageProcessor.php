<?php

namespace App\Media\Processors;

use App\Media\MediaProcessor;
use Illuminate\Http\File;
use GdImage;
use Illuminate\Support\Facades\Storage;

class ImageProcessor implements MediaProcessor
{
    public GdImage $image;
    public float $aspectRatio;
    public int $maxWidth;
    public int $maxHeight;
    public string $distPath;

    public function __construct(private File $file, int $maxWidth = 800, int $maxHeight = 600)
    {
        $this->maxWidth = $maxWidth;
        $this->maxHeight = $maxHeight;
    }

    /**
     * Processes the image, resizes it, and converts it to JPEG
     */
    public function process(string $distPath): string
    {
        $this->distPath = $distPath;
        [$w, $h, $type] = getimagesize($this->file->getRealPath());
        $this->image = match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($this->file->getRealPath()),
            IMAGETYPE_PNG => imagecreatefrompng($this->file->getRealPath()),
            IMAGETYPE_GIF => imagecreatefromgif($this->file->getRealPath()),
            default => throw new \Exception('Unsupported image type'),
        };

        if ($this->image === false) {
            throw new \Exception('Failed to create image from file');
        }

        $this->aspectRatio = $w / $h;
        $this->resize($w, $h);
        return $this->save($this->distPath);
    }

    /**
     * Resizes the image to fit within maxWidth and maxHeight while preserving aspect ratio
     * @param int $originalWidth
     * @param int $originalHeight
     */
    public function resize($originalWidth, $originalHeight)
    {
        // Calculate new dimensions
        if ($originalWidth > $this->maxWidth || $originalHeight > $this->maxHeight) {
            if (($this->maxWidth / $this->maxHeight) > $this->aspectRatio) {
                $newWidth = (int) ($this->maxHeight * $this->aspectRatio);
                $newHeight = $this->maxHeight;
            } else {
                $newWidth = $this->maxWidth;
                $newHeight = (int) ($this->maxWidth / $this->aspectRatio);
            }
        } else {
            $newWidth = $originalWidth;
            $newHeight = $originalHeight;
        }

        // Create new image resource
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        if ($newImage === false) {
            throw new \Exception('Failed to create new image resource');
        }

        // Set white background for JPEG (non-transparent)
        $white = imagecolorallocate($newImage, 255, 255, 255);
        imagefill($newImage, 0, 0, $white);

        // Copy and resize the original image
        if (!imagecopyresampled($newImage, $this->image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight)) {
            throw new \Exception('Failed to resize image');
        }

        // Free the original image resource
        imagedestroy($this->image);
        $this->image = $newImage;
    }

    /**
     * Returns the processed JPEG image resource
     * @return GdImage
     */
    public function getNew()
    {
        return $this->image;
    }

    /**
     * Saves the processed image as JPEG
     * @param string $path
     * @param intstrateg: int $quality (0-100, default 75)
     */
    public function save(string $path, int $quality = 75)
    {
        $filename = $path . "/" . uniqid() . ".jpeg";

        ob_start();
        if (!imagejpeg($this->image, null, $quality)) {
            throw new \Exception('Failed to encode JPEG image');
        }
        $imageData = ob_get_clean();

        Storage::disk('local')->put($filename, $imageData);

        return $filename;
    }
}
