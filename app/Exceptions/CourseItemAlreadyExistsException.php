<?php

namespace App\Exceptions;


class CourseItemAlreadyExistsException extends BaseException
{
    public function __construct()
    {
        parent::__construct("Course item with this title already exist", 'course_item', 400);
    }
}
