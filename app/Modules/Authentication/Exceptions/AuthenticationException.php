<?php

namespace App\Modules\Authentication\Exceptions;

use Exception;

class AuthenticationException extends Exception
{
    public function __construct(string $message = 'Authentication failed')
    {
        parent::__construct($message);
    }
}