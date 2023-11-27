<?php

namespace Oilstone\RsqlParser\Operators;

/**
 * Class NotLike
 * @package Oilstone\RsqlParser\Operators
 */
class NotLike extends Operator
{
    /**
     * @var string
     */
    protected $uri = '=not-like=';

    /**
     * @var string
     */
    protected $sql = 'NOT LIKE';
}
