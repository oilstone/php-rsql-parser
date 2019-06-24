<?php

namespace Oilstone\RsqlParser\Operators;

/**
 * Class NotEqualTo
 * @package Oilstone\RsqlParser\Operators
 */
class NotEqualTo extends Operator
{
    /**
     * @var string
     */
    protected $uri = '=!=';

    /**
     * @var string
     */
    protected $sql = '!=';
}