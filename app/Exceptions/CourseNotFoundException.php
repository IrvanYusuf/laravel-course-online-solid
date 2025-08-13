<?php

namespace App\Exceptions;


class CourseNotFoundException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Course with this id not exist', 'category', 404);
    }
}
