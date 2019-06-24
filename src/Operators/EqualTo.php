<?php

namespace Oilstone\RsqlParser\Operators;

/**
 * Class EqualTo
 * @package Oilstone\RsqlParser\Operators
 */
class EqualTo extends Operator
{
    /**
     * @var string
     */
    protected $uri = '==';

    /**
     * @var string
     */
    protected $sql = '=';
}