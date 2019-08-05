<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    public function __construct(array $apiErrConst, \Throwable $previous = null)
    {
        parent::__construct($apiErrConst[1], $apiErrConst[0], $previous);
    }
}
