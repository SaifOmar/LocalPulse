<?php

namespace App\Helpers\Images;

// INFO: need to be completed
class ImageCompressor
{
    public static function compressImage($file)
    {
        switch (strtolower(pathinfo($file, PATHINFO_EXTENSION))) {
            case 'jpg':
            case 'jpeg':
                self->compressJpeg($file);
                break;
            case 'png':
                self->compressPng($file);
                break;
            case 'gif':
                self->compressGif($file);
                break;
            default:
                return false;
        }
        $image = imagecreatefromjpeg($file);
        $width = imagesx($image);
        $height = imagesy($image);
        $new_width = 1000;
        $new_height = 1000;
        $new_image = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagejpeg($new_image, $file);
    }
    public static function compressJpeg($file)
    {
        $image = imagecreatefromjpeg($file);
        $width = imagesx($image);
        $height = imagesy($image);
    }
    public static function compressPng($file)
    {
        $image = imagecreatefrompng($file);
        $width = imagesx($image);
        $height = imagesy($image);
        imagepng($image, $file);
    }
    public static function compressGif($file)
    {
        $image = imagecreatefromgif($file);
        $width = imagesx($image);
        $height = imagesy($image);
        imagegif($image, $file);
    }
}
