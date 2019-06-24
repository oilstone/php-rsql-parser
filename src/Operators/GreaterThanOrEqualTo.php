<?php

namespace Oilstone\RsqlParser\Operators;

/**
 * Class GreaterThanOrEqualTo
 * @package Oilstone\RsqlParser\Operators
 */
class GreaterThanOrEqualTo extends Operator
{
    /**
     * @var string
     */
    protected $uri = '=gte=';

    /**
     * @var string
     */
    protected $sql = '>=';
}