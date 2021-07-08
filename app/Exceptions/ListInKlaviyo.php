<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class ListInKlaviyo extends Exception
{
    public static function notFoundById($message, Throwable $previous = null): ListInKlaviyo {
        return new static($message, 0, $previous);
    }
}
