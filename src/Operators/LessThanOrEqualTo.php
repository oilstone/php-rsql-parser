<?php

namespace Oilstone\RsqlParser\Operators;

/**
 * Class LessThanOrEqualTo
 * @package Oilstone\RsqlParser\Operators
 */
class LessThanOrEqualTo extends Operator
{
    /**
     * @var string
     */
    protected $uri = '=lte=';

    /**
     * @var string
     */
    protected $sql = '<=';
}