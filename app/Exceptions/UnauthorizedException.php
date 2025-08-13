<?php

namespace App\Exceptions;


class UnauthorizedException extends BaseException
{

    public function __construct()
    {
        parent::__construct("Unauthorized", 'category', 403);
    }
}
