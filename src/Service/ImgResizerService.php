<?php

declare(strict_types=1);

namespace Jukuan\ImgToWeb\Service;

use GdImage;
use Jukuan\ImgToWeb\Exceptions\BadRequestException;
use Jukuan\ImgToWeb\Exceptions\CannotConvert;
use Jukuan\ImgToWeb\Http\Request;

class ImgResizerService
{
    private string $defaultExtension = 'webp';

    public function setDefaultExtension(string $defaultExtension): ImgResizerService
    {
        $this->defaultExtension = $defaultExtension;

        return $this;
    }

    /**
     * @throws CannotConvert
     */
    public function createGdImage(string $sourceImage): GdImage
    {
        $extension = pathinfo($sourceImage, PATHINFO_EXTENSION);
        $extension = strtolower($extension);

        if ('jpg' === $extension || 'jpeg' === $extension) {
            $image = imagecreatefromjpeg($sourceImage);
        } elseif ('png' === $extension) {
            $image = imagecreatefrompng($sourceImage);
        } elseif ('webp' === $extension) {
            throw (new CannotConvert())->setSourceFilePath($sourceImage);
        } else {
            throw new BadRequestException();
        }

        return $image;
    }

    public function writeGdImage(GdImage $image, string $newWebPath, ?string $ext = null): void
    {
        $ext = $ext ? strtolower($ext) : $this->defaultExtension;

        if (str_starts_with($ext, 'jp')) {
            // jpg/jpeg files
            imagejpeg($image, $newWebPath, 80);
        } elseif ('png' === $ext) {
            imagepng($image, $newWebPath, 8);
        } else {
            imagewebp($image, $newWebPath);
        }
    }

    /**
     * @throws CannotConvert
     */
    public function resizeImage(string $sourcePath, string $newWebPath, Request $request): void
    {
        $image = $this->createGdImage($sourcePath);

        $width = $request->getWidth();
        $height = $request->getHeight();

        if ($width && $height) {
            $resizedImage = imagecreatetruecolor($width, $height);
            imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));
            $image = $resizedImage;
        } elseif ($width) {
            $height = (int) round(imagesy($image) * ($width / imagesx($image)));
            $resizedImage = imagecreatetruecolor($width, $height);
            imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));
            $image = $resizedImage;
        } elseif ($height) {
            $width = (int) round(imagesx($image) * ($height / imagesy($image)));
            $resizedImage = imagecreatetruecolor($width, $height);
            imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));
            $image = $resizedImage;
        }

        $this->writeGdImage($image, $newWebPath, $request->getExt());

        imagedestroy($image);
    }
}
