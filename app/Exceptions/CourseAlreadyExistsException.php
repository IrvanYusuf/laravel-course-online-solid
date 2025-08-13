<?php

namespace App\Exceptions;

use Exception;

class CourseAlreadyExistsException extends BaseException
{
    public function __construct()
    {
        parent::__construct("Course with this title already exists.", 'course', 400);
    }
}
