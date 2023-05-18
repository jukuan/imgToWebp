<?php

declare(strict_types=1);

namespace Jukuan\ImgToWeb\Http;

use Jukuan\ImgToWeb\Exceptions\BadRequestException;

class Request
{
    private string $imageIri;

    private int $width;

    private int $height;

    private ?string $ext;

    /**
     * @throws BadRequestException
     */
    public function __construct()
    {
        try {
            $this->imageIri = filter_input(INPUT_GET, 'img') ?? '';
            $this->ext = filter_input(INPUT_GET, 'ext') ?? null;
            $this->width = (int) ($_GET['w'] ?? 0);
            $this->height = (int) ($_GET['h'] ?? 0);

            if ($this->imageIri) {
                $this->imageIri = ltrim($this->imageIri, '/');
            }
        } catch (\Exception $e) {
            throw new BadRequestException($e->getMessage(), 400, $e);
        }
    }

    public function getImageIri(): ?string
    {
        return $this->imageIri;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getExt(): ?string
    {
        return $this->ext;
    }
}
