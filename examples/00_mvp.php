<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$origImgPath = $_GET['img'] ?? null;
$origImgPath = $origImgPath ? __DIR__ . '/' . ltrim($origImgPath, '/') : null;

if (!file_exists($origImgPath)) {
    header('HTTP/1.0 404 Not Found');
    die();
}

$resizedPath = __DIR__ . '/resized';

try {
    $fileInfo = pathinfo($origImgPath);
    $dirName = $fileInfo['dirname'] ?? '';      // '/var/www/public_html/uploads/images'
    $basename = $fileInfo['basename'] ?? '';    // 'dogs.jpg'
    $extension = $fileInfo['extension'] ?? '';  // 'jpg'
    $filename = $fileInfo['filename'] ?? '';    // 'dogs'

    $imgWidth = $_GET['w'] ?? null;
    $imgHeight = $_GET['h'] ?? null;

    $pathPrefix = str_replace(__DIR__, '', $dirName);

    $newFileName = $filename . '.webp';
    $webpPath = $resizedPath . $pathPrefix;

    $sizePrefix = '';

    if ($imgWidth && $imgHeight) {
        $sizePrefix = $imgWidth . 'x' . $imgHeight;
    } else if ($imgWidth) {
        $sizePrefix = 'w' . $imgWidth;
    } else if ($imgHeight) {
        $sizePrefix = 'h' . $imgHeight;
    }

    if ($sizePrefix) {
        $webpPath .= '/' . $sizePrefix;
    }

    if (!file_exists($webpPath)) {
        mkdir($webpPath, 0755, true);
    }

    $webpPath .= '/' . $newFileName;

    if (!file_exists($webpPath)) {
        if ($extension === 'jpg' || $extension === 'jpeg') {
            $image = imagecreatefromjpeg($origImgPath);
        } elseif ($extension === 'png') {
            $image = imagecreatefrompng($origImgPath);
        } elseif ($extension === 'webp') {
            header('Content-Type: image/webp');
            readfile($origImgPath);
            die();
        } else {
            header('HTTP/1.0 400 Bad Request');
            die();
        }

        if ($imgWidth && $imgHeight) {
            $resizedImage = imagecreatetruecolor($imgWidth, $imgHeight);
            imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $imgWidth, $imgHeight, imagesx($image), imagesy($image));
            $image = $resizedImage;
        } elseif ($imgWidth) {
            $imgHeight = round(imagesy($image) * ($imgWidth / imagesx($image)));
            $resizedImage = imagecreatetruecolor($imgWidth, $imgHeight);
            imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $imgWidth, $imgHeight, imagesx($image), imagesy($image));
            $image = $resizedImage;
        } elseif ($imgHeight) {
            $imgWidth = round(imagesx($image) * ($imgHeight / imagesy($image)));
            $resizedImage = imagecreatetruecolor($imgWidth, $imgHeight);
            imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $imgWidth, $imgHeight, imagesx($image), imagesy($image));
            $image = $resizedImage;
        }

        imagewebp($image, $webpPath);
        imagedestroy($image);
    }

    header('Content-Type: image/webp');
    readfile($webpPath);
} catch (Exception $e) {
    header('HTTP/1.0 404 Not Found');
    die();
}
