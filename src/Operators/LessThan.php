<?php

namespace Oilstone\RsqlParser\Operators;

/**
 * Class LessThan
 * @package Oilstone\RsqlParser\Operators
 */
class LessThan extends Operator
{
    /**
     * @var string
     */
    protected $uri = '=lt=';

    /**
     * @var string
     */
    protected $sql = '<';
}