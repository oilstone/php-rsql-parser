<?php

namespace Oilstone\RsqlParser\Operators;

/**
 * Class Between
 * @package Oilstone\RsqlParser\Operators
 */
class Between extends Operator
{
    /**
     * @var string
     */
    protected $uri = '=between=';

    /**
     * @var string
     */
    protected $sql = 'BETWEEN';

    /**
     * @var bool
     */
    protected $expectsArray = true;
}