<?php

declare(strict_types=1);

namespace Jukuan\ImgToWeb\Util;

use Jukuan\ImgToWeb\Exceptions\ConfigurationException;
use Jukuan\ImgToWeb\Exceptions\NotFoundException;
use Jukuan\ImgToWeb\Http\Request;

class FilePathGenerator
{
    protected function doSizePrefix(int $width, int $height): string
    {
        $sizePrefix = '';

        if ($width && $height) {
            $sizePrefix = $width . 'x' . $height;
        } elseif ($width) {
            $sizePrefix = 'w' . $width;
        } elseif ($height) {
            $sizePrefix = 'h' . $height;
        }

        return $sizePrefix;
    }

    /**
     * @throws ConfigurationException
     * @throws NotFoundException
     */
    public function doDestinationFilePath(string $destinationDir, Request $request, string $newExt = 'webp'): string
    {
        $imgIri = $request->getImageIri() ?? '';

        if (!$imgIri) {
            throw new NotFoundException();
        }

        $imagePath = $destinationDir . '/' . $imgIri;
        $imageDir = dirname($imagePath);

        if ($sizePrefix = $this->doSizePrefix($request->getWidth(), $request->getHeight())) {
            $imageDir .= '/' . $sizePrefix;
        }

        DirectoryHelper::prepareSubdirectory($imageDir);
        $filename = pathinfo($imagePath, PATHINFO_FILENAME);

        return sprintf('%s/%s.%s', $imageDir, $filename, $newExt);
    }
}
