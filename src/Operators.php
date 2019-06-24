<?php

namespace Oilstone\RsqlParser;

use Oilstone\RsqlParser\Exceptions\UnidentifiedOperatorException;
use Oilstone\RsqlParser\Operators\Between;
use Oilstone\RsqlParser\Operators\EqualTo;
use Oilstone\RsqlParser\Operators\GreaterThan;
use Oilstone\RsqlParser\Operators\GreaterThanOrEqualTo;
use Oilstone\RsqlParser\Operators\In;
use Oilstone\RsqlParser\Operators\IsNotNull;
use Oilstone\RsqlParser\Operators\IsNull;
use Oilstone\RsqlParser\Operators\LessThan;
use Oilstone\RsqlParser\Operators\LessThanOrEqualTo;
use Oilstone\RsqlParser\Operators\Like;
use Oilstone\RsqlParser\Operators\NotEqualTo;
use Oilstone\RsqlParser\Operators\NotIn;
use Oilstone\RsqlParser\Operators\Operator;

/**
 * Class Operators
 * @package Oilstone\RsqlParser
 */
class Operators
{
    /**
     * @var array
     */
    protected static $operators = [
        Between::class,
        EqualTo::class,
        GreaterThan::class,
        GreaterThanOrEqualTo::class,
        In::class,
        IsNotNull::class,
        IsNull::class,
        LessThan::class,
        LessThanOrEqualTo::class,
        Like::class,
        NotEqualTo::class,
        NotIn::class,
    ];

    /**
     * @param $operator
     * @throws UnidentifiedOperatorException
     */
    public static function custom($operator)
    {
        $operator = static::make($operator);

        static::$operators[] = $operator;
    }

    /**
     * @param $operator
     * @return Operator|null
     * @throws UnidentifiedOperatorException
     */
    protected static function make($operator): ?Operator
    {
        if (!$operator instanceof Operator && class_exists($operator)) {
            $operator = new $operator;
        } else {
            $operator = null;
        }

        if (!$operator instanceof Operator) {
            throw new UnidentifiedOperatorException('An unknown operator was detected');
        }

        return $operator;
    }

    /**
     * @return array
     */
    public static function all(): array
    {
        return array_map(function ($operator) {
            return static::make($operator);
        }, static::$operators);
    }

    /**
     * @param string $uriComponent
     * @return Operator|null
     */
    public static function byUri(string $uriComponent): ?Operator
    {
        foreach (static::$operators as $operator) {
            try {
                $operator = static::make($operator);
            } catch (UnidentifiedOperatorException $e) {
                //
            }

            if ($operator->toUri() === $uriComponent) {
                return $operator;
            }
        }

        return null;
    }

    /**
     * @param string $uriComponent
     * @return Operator|null
     */
    public static function bySql(string $uriComponent): ?Operator
    {
        foreach (static::$operators as $operator) {
            try {
                $operator = static::make($operator);
            } catch (UnidentifiedOperatorException $e) {
                //
            }

            if ($operator->toSql() === $uriComponent) {
                return $operator;
            }
        }

        return null;
    }
}