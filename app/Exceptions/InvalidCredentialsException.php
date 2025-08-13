<?php

namespace App\Exceptions;


class InvalidCredentialsException extends BaseException
{
    public function __construct()
    {
        parent::__construct("Email or Password is incorrect", 'credentials', 401);
    }
}
