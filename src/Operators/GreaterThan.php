<?php

namespace Oilstone\RsqlParser\Operators;

/**
 * Class GreaterThan
 * @package Oilstone\RsqlParser\Operators
 */
class GreaterThan extends Operator
{
    /**
     * @var string
     */
    protected $uri = '=gt=';

    /**
     * @var string
     */
    protected $sql = '>';
}