<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ValidationException extends \RuntimeException
{
    /**
     * @var array
     */
    protected $errors = [];

    public function __construct($message = '', $code = 0, \Throwable $previous = null, array $errors = [])
    {
        $this->errors = $errors;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     *
     * @return \App\Exceptions\ValidationException
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;

        return $this;
    }
}
