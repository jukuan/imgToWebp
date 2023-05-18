<?php

declare(strict_types=1);

namespace Jukuan\ImgToWeb\Util;

use Jukuan\ImgToWeb\Exceptions\ConfigurationException;

class DirectoryHelper
{
    public static int $dirPermissions = 0755;

    public static function normalizeSlashes(string $path): string
    {
        return '/' . trim($path, '/');
    }

    /**
     * @throws ConfigurationException
     */
    public static function prepareSubdirectory(string $path): void
    {
        if (file_exists($path)) {
            return;
        }

        if (!mkdir($path, self::$dirPermissions, true)) {
            throw new ConfigurationException(
                'Cannot create a directory: ' . $path,
                422
            );
        }
    }
}
