<?php

declare(strict_types=1);

namespace Jukuan\ImgToWeb\Service;

use Jukuan\ImgToWeb\Exceptions\CannotConvert;
use Jukuan\ImgToWeb\Exceptions\ConfigurationException;
use Jukuan\ImgToWeb\Exceptions\NotFoundException;
use Jukuan\ImgToWeb\Http\Request;
use Jukuan\ImgToWeb\Util\DirectoryHelper;
use Jukuan\ImgToWeb\Util\FilePathGenerator;

class ImgConvertorService
{
    private ?string $destinationDir = null;
    private ?string $sourceDir = null;

    public function setDirs(string $destinationDir, string $sourceDir): ImgConvertorService
    {
        $this->destinationDir = DirectoryHelper::normalizeSlashes($destinationDir);
        $this->sourceDir = DirectoryHelper::normalizeSlashes($sourceDir);

        return $this;
    }

    /**
     * @throws ConfigurationException
     * @throws NotFoundException
     * @throws CannotConvert
     */
    public function prepareImagePath(): string
    {
        if (!$this->sourceDir) {
            throw new ConfigurationException();
        }

        $request = new Request();
        $imgIri = $request->getImageIri() ?? '';
        $sourceFile = $this->sourceDir . '/' . $imgIri;

        if (!file_exists($sourceFile)) {
            throw new NotFoundException('The source file does not exist: ' . $sourceFile);
        }

        if (!$this->destinationDir || !file_exists($this->destinationDir)) {
            throw new NotFoundException('The destination directory does not exist: ' . ($this->destinationDir ?? ''));
        }

        $mime = $this->getFileMimeType($sourceFile);

        if (!str_starts_with($mime, 'image/')) {
            throw new NotFoundException('The source file has to be valid image!');
        }

        $destinationFile = (new FilePathGenerator())->doDestinationFilePath($this->destinationDir, $request);

        if (!file_exists($destinationFile)) {
            (new ImgResizerService())
                ->resizeImage($sourceFile, $destinationFile, $request);
        }

        return $destinationFile;
    }

    private function getFileMimeType(string $filePath)
    {
        try {
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);

            return finfo_file($fileInfo, $filePath);
        } catch (\Exception $e) {
        }

        return '';
    }
}
