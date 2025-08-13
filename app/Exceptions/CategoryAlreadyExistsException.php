<?php

namespace App\Exceptions;

use Exception;

class CategoryAlreadyExistsException extends Exception
{
    protected $message = "Category with this name allready exists";
}
