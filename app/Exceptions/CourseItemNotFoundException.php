<?php

namespace App\Exceptions;


class CourseItemNotFoundException extends BaseException
{
    public function __construct()
    {
        parent::__construct("Course item not found", 'course_item', 404);
    }
}
