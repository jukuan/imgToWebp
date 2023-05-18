<?php

declare(strict_types=1);

namespace Jukuan\ImgToWeb\Http;

class Response
{
    public function doNotFound(): never
    {
        header('HTTP/1.0 404 Not Found');
        die();
    }

    public function doImageOutput(string $webpPath, string $imgType = 'image/webp'): never
    {
        if (!file_exists($webpPath)) {
            $this->doNotFound();
        }

        header('Content-Type: ' . $imgType);
        readfile($webpPath);
        die();
    }
}
