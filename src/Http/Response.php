<?php

declare(strict_types=1);

namespace Jukuan\ImgToWeb\Http;

class Response
{
    /**
     * @return never
     */
    public function doNotFound(): void
    {
        header('HTTP/1.0 404 Not Found');
        die();
    }

    /**
     * @return never
     */
    public function doImageOutput(string $webpPath, string $imgType = 'image/webp'): void
    {
        if (!file_exists($webpPath)) {
            $this->doNotFound();
        }

        header('Content-Type: ' . $imgType);
        readfile($webpPath);
        die();
    }
}
