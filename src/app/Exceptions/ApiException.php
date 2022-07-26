<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiException extends HttpException
{
    /**
     * @var array
     */
    protected $errors = [];

    public function __construct(int $statusCode, $message = null, array $errors = [], \Exception $previous = null, array $headers = [], $code = 0)
    {
        $this->errors = $errors;

        parent::__construct($statusCode, $message, $previous, $headers, 1000);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
