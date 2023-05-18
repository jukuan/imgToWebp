<?php

declare(strict_types=1);

namespace Jukuan\ImgToWeb\Exceptions;

use Exception;

class CannotConvert extends Exception
{
    private ?string $sourceFilePath = null;

    public function getSourceFilePath(): ?string
    {
        return $this->sourceFilePath;
    }

    public function setSourceFilePath(?string $sourceFilePath): CannotConvert
    {
        $this->sourceFilePath = $sourceFilePath;

        return $this;
    }
}
