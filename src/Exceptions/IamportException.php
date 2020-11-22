<?php

namespace Blood72\Iamport\Exceptions;

use Exception;
use Throwable;

abstract class IamportException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
