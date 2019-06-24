<?php

namespace Oilstone\RsqlParser\Operators;

/**
 * Class In
 * @package Oilstone\RsqlParser\Operators
 */
class In extends Operator
{
    /**
     * @var string
     */
    protected $uri = '=in=';

    /**
     * @var string
     */
    protected $sql = 'IN';

    /**
     * @var bool
     */
    protected $expectsArray = true;
}