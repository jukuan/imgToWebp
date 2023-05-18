<?php

declare(strict_types=1);

namespace Jukuan\ImgToWeb\Exceptions;

use Exception;

class ConfigurationException extends Exception
{
    public function __construct(string $message = '', int $code = 0, ?Exception $e = null)
    {
        $message = $message ?: 'The script is not configured correctly';
        parent::__construct($message, $code, $e);
    }
}
