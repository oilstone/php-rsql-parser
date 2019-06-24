<?php

namespace Oilstone\RsqlParser\Exceptions;

use Exception as BaseException;
use Throwable;

/**
 * Class Exception
 * @package Oilstone\RsqlParser\Exceptions
 */
class Exception extends BaseException
{
    /**
     * InvalidQueryStringException constructor.
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}