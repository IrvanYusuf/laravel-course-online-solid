<?php

namespace App\Exceptions;

use Exception;

class UserAlreadyExistsException extends Exception
{
    protected $message = "User with this email already exists.";
}
