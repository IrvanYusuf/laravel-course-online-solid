<?php

namespace App\Exceptions;


class UserAlreadyExistsException extends BaseException
{
    protected $message = "User with this email already exists.";

    public function __construct()
    {
        parent::__construct("User with this email already exists.", 'category', 400);
    }
}
