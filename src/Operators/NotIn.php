<?php

namespace Oilstone\RsqlParser\Operators;

/**
 * Class NotIn
 * @package Oilstone\RsqlParser\Operators
 */
class NotIn extends Operator
{
    /**
     * @var string
     */
    protected $uri = '=not-in=';

    /**
     * @var string
     */
    protected $sql = 'NOT IN';

    /**
     * @var bool
     */
    protected $expectsArray = true;
}