<?php

namespace Oilstone\RsqlParser\Operators;

/**
 * Class IsNotNull
 * @package Oilstone\RsqlParser\Operators
 */
class IsNotNull extends Operator
{
    /**
     * @var string
     */
    protected $uri = '=not-null=';

    /**
     * @var string
     */
    protected $sql = 'IS NOT NULL';

    /**
     * @var bool
     */
    protected $valueRequired = false;
}