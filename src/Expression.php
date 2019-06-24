<?php

namespace Oilstone\RsqlParser;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * Class Expression
 * @package Oilstone\RsqlParser
 */
class Expression implements ArrayAccess, Countable, IteratorAggregate
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * Expression constructor.
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * @param $constraint
     * @return Expression
     */
    public function and($constraint): Expression
    {
        return $this->add('AND', $constraint);
    }

    /**
     * @param string $operator
     * @param $constraint
     * @return Expression
     */
    public function add(string $operator, $constraint): Expression
    {
        $this->items[] = [
            'operator' => $operator,
            'constraint' => $constraint
        ];

        return $this;
    }

    /**
     * @param $constraint
     * @return Expression
     */
    public function or($constraint): Expression
    {
        return $this->add('OR', $constraint);
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset] ?? null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->items[$offset] = $value;
    }

    /**
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->items[$offset]);
        }
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }
}