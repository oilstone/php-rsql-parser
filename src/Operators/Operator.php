<?php

namespace Oilstone\RsqlParser\Operators;

/**
 * Class Operator
 * @package Oilstone\RsqlParser\Operators
 */
abstract class Operator
{
    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string
     */
    protected $sql;

    /**
     * @var bool
     */
    protected $expectsArray = false;

    /**
     * @var bool
     */
    protected $valueRequired = true;

    /**
     * @return string
     */
    public function toSql(): string
    {
        return $this->sql;
    }

    /**
     * @return string
     */
    public function toUri(): string
    {
        return $this->uri;
    }

    /**
     * @return bool
     */
    public function expectsArray(): bool
    {
        return $this->expectsArray;
    }

    /**
     * @return bool
     */
    public function valueRequired(): bool
    {
        return $this->valueRequired;
    }
}