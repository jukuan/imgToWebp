<?php

declare(strict_types=1);

namespace Jukuan\ImgToWeb\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    public function __construct(string $message = '', int $code = 0, ?Exception $e = null)
    {
        $message = $message ?: 'The source image does not exist';
        parent::__construct($message, $code, $e);
    }
}
