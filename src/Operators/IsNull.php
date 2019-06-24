<?php

namespace Oilstone\RsqlParser\Operators;

/**
 * Class IsNull
 * @package Oilstone\RsqlParser\Operators
 */
class IsNull extends Operator
{
    /**
     * @var string
     */
    protected $uri = '=null=';

    /**
     * @var string
     */
    protected $sql = 'IS NULL';

    /**
     * @var bool
     */
    protected $valueRequired = false;
}