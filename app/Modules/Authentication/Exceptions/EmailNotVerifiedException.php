<?php

namespace App\Modules\Authentication\Exceptions;

class EmailNotVerifiedException extends AuthenticationException
{
    public function __construct(string $message = 'Email not verified')
    {
        parent::__construct($message);
    }
}