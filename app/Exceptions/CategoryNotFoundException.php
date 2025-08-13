<?php

namespace App\Exceptions;


class CategoryNotFoundException extends BaseException
{
    public function __construct()
    {
        parent::__construct("Category not found", 'category', 404);
    }
}
