<?php

namespace App\Exceptions;

use Exception;

class UserNotFoundException extends BaseException
{
    protected $message = "User not found";
    public function __construct()
    {
        parent::__construct("User not found", 'category', 404);
    }
}
