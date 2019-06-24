<?php

namespace Oilstone\RsqlParser\Operators;

/**
 * Class Like
 * @package Oilstone\RsqlParser\Operators
 */
class Like extends Operator
{
    /**
     * @var string
     */
    protected $uri = '=like=';

    /**
     * @var string
     */
    protected $sql = 'LIKE';
}