<?php

namespace App\Modules\Authentication\Exceptions;

class InvalidCredentialsException extends AuthenticationException
{
    public function __construct(string $message = 'Invalid credentials')
    {
        parent::__construct($message);
    }
}