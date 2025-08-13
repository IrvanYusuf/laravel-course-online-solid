<?php

namespace App\Exceptions;

use Exception;

abstract class BaseException extends Exception
{
    protected int $statusCode;
    protected string $errorField;

    public function __construct(string $message, string $errorField, int $statusCode)
    {
        parent::__construct($message);
        $this->errorField = $errorField;
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getErrorField(): string
    {
        return $this->errorField;
    }
}
