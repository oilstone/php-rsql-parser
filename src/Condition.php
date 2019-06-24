<?php


namespace Oilstone\RsqlParser;

use Oilstone\RsqlParser\Exceptions\ConditionFromStringException;
use Oilstone\RsqlParser\Support\Str;
use Oilstone\RsqlParser\Operators\Operator;

/**
 * Class Condition
 * @package Oilstone\RsqlParser
 */
class Condition
{
    /**
     * @var string
     */
    protected $column;

    /**
     * @var Operator
     */
    protected $operator;

    /**
     * @var null
     */
    protected $value;

    /**
     * Condition constructor.
     * @param string $column
     * @param Operator $operator
     * @param null $value
     */
    public function __construct(string $column, Operator $operator, $value = null)
    {
        $this->column = $column;
        $this->operator = $operator;
        $this->value = Str::unescape($value);
    }

    /**
     * @param string $condition
     * @return Condition
     * @throws ConditionFromStringException
     */
    public static function fromString(string $condition): Condition
    {
        foreach (Operators::all() as $operator) {
            /** @var Operator $operator */
            $operatorPos = stripos($condition, $operator->toUri());
            $value = null;

            if ($operatorPos !== false) {
                if ($operator->valueRequired()) {
                    $value = trim(trim(substr($condition, $operatorPos + strlen($operator->toUri()))), '\'"');

                    if ($operator->expectsArray()) {
                        $value = array_map('trim', explode(",", substr($value, 1, strlen($value) - 2)));
                    }
                }

                return new Condition(
                    substr($condition, 0, $operatorPos),
                    $operator,
                    $value
                );
            }
        }

        throw new ConditionFromStringException('Failed to identify a valid operation when parsing a condition from a string');
    }

    /**
     * @return string
     */
    public function getColumn(): string
    {
        return $this->column;
    }

    /**
     * @param string $column
     * @return Condition
     */
    public function setColumn(string $column): Condition
    {
        $this->column = $column;

        return $this;
    }

    /**
     * @return Operator
     */
    public function getOperator(): Operator
    {
        return $this->operator;
    }

    /**
     * @param Operator $operator
     * @return Condition
     */
    public function setOperator(Operator $operator): Condition
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * @return null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param null $value
     * @return Condition
     */
    public function setValue($value)
    {
        $this->value = Str::unescape($value);

        return $this;
    }
}