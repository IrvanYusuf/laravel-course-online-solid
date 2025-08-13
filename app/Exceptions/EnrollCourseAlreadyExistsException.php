<?php

namespace App\Exceptions;

use Exception;

class EnrollCourseAlreadyExistsException extends BaseException
{
    public function __construct()
    {
        parent::__construct("Student is already enrolled in this course.", 'enroll', 400);
    }
}
