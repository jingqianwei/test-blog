<?php

namespace App\Exceptions;

use Exception;

class InvalidSystemClockException extends Exception
{
    public function __construct($message, int $code = 500)
    {
        parent::__construct($message, $code);
    }
}
