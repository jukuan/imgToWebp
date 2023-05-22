<?php

declare(strict_types=1);

namespace Tests\Service;

use Jukuan\ImgToWeb\Service\ImgResizerService;
use PHPUnit\Framework\TestCase;

final class ImgResizerServiceTest extends TestCase
{
    private ImgResizerService $imgResizer;

    protected function setUp(): void
    {
        $this->imgResizer = new ImgResizerService();
    }

    public function testCreateGdImage(): void
    {
        $sourceImage = 'examples/static/uploads/guy.jpg';
        $gdImage = $this->imgResizer->createGdImage($sourceImage);

        $this->assertSame(512, (int)imagesx($gdImage));
        $this->assertSame(442, (int)imagesy($gdImage));

        imagedestroy($gdImage);
    }

    public function testWriteGdImage(): void
    {
        $sourceImage = 'examples/static/uploads/guy.jpg';
        $gdImage = imagecreatefromjpeg($sourceImage);

        $newWebPath = __DIR__ . '/../../examples/resized/static/uploads/guy.webp';
        $expectedExt = 'webp';

        $this->imgResizer->writeGdImage($gdImage, $newWebPath, $expectedExt);

        $this->assertFileExists($newWebPath);
        $this->assertStringContainsString($expectedExt, strtolower(file_get_contents($newWebPath)));
        unlink($newWebPath);
    }
}
