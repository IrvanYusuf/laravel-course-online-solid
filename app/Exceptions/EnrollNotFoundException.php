<?php

namespace App\Exceptions;


class EnrollNotFoundException extends BaseException
{
    public function __construct()
    {
        parent::__construct("Enrollment not found.", 'enroll', 404);
    }
}
