<?php

namespace App\Exceptions;


class CategoryAlreadyExistsException extends BaseException
{
    public function __construct()
    {
        parent::__construct("Category with this name allready exists", 'category', 400);
    }
}
