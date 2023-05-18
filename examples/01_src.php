<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Jukuan\ImgToWeb\Exceptions\CannotConvert;
use Jukuan\ImgToWeb\Http\Response;
use Jukuan\ImgToWeb\Service\ImgConvertorService;

$vendorDir = dirname(__DIR__) . '/vendor';
require_once $vendorDir . '/autoload.php';

$uploadedImgDir = __DIR__ . '/static';
$resizedImgDir = __DIR__ . '/resized';

try {
    $imagePath = (new ImgConvertorService())
        ->setDirs($resizedImgDir, __DIR__)
        ->prepareImagePath();
    (new Response())->doImageOutput($imagePath);
} catch (CannotConvert $e) {
    if ($e->getSourceFilePath()) {
        (new Response())->doImageOutput($e->getSourceFilePath());
    }
    (new Response())->doNotFound();
} catch (\Exception $e) {
    (new Response())->doNotFound();
}
