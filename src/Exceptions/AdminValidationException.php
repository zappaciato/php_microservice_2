<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class AdminValidationException extends Exception
{
        public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        $message = 'Admin Validation Failed! Sorry!';
        parent::__construct($message, $code, $previous);
    }

}