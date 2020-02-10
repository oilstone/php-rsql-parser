<?php

namespace Oilstone\RsqlParser;

use Exception;
use Oilstone\RsqlParser\Exceptions\InvalidQueryStringException;
use Oilstone\RsqlParser\Support\Str;

/**
 * Class Parser
 * @package Oilstone\RsqlParser
 */
class Parser
{
    /**
     * @param string $query
     * @return Expression
     * @throws InvalidQueryStringException
     */
    public static function parse(string $query): Expression
    {
        $parser = new Parser();

        $segments = $parser->segment(Str::escape($query));

        $segments = $parser->initialiseOperators($segments);

        $segments = $parser->mergeOperators($segments);

        return $parser->expression($segments);
    }

    /**
     * @param string $query
     * @return array
     * @throws InvalidQueryStringException
     */
    protected function segment(string $query): array
    {
        $segments = [];
        $nextSegmentStart = Str::findUnescapedInstance($query, '(', 0, ['=']);

        $segments[] = $nextSegmentStart !== false ? substr($query, 0, $nextSegmentStart) : $query;

        if ($nextSegmentStart !== false) {
            try {
                $nextSegmentEnd = Str::findClosingBracket($query, $nextSegmentStart);
            } catch (Exception $e) {
                throw new InvalidQueryStringException($e->getMessage(), $e);
            }

            $segments[] = $this->segment(substr($query, $nextSegmentStart + 1, ($nextSegmentEnd - $nextSegmentStart) - 1));

            $segments = array_merge($segments, $this->segment(substr($query, $nextSegmentEnd + 1)));
        }

        return $segments;
    }

    /**
     * @param array $segments
     * @return array
     */
    protected function initialiseOperators(array $segments): array
    {
        $segments = array_values(array_map(function ($segment) {
            $operator = 'AND';

            if (is_string($segment) && strpos($segment, ',') === 0) {
                $operator = 'OR';
                $segment = substr($segment, 1);
            }

            if (is_string($segment) && strpos($segment, ';') === 0) {
                $operator = 'AND';
                $segment = substr($segment, 1);
            }

            return [
                'initialOperator' => $operator,
                'conditions' => is_array($segment) ? $this->initialiseOperators($segment) : $segment,
            ];
        }, $segments));

        $segmentLength = count($segments);

        for ($i = 0; $i < $segmentLength; $i++) {
            $conditions = $segments[$i]['conditions'];

            if (is_string($conditions) && substr($conditions, -1) === ';' && substr($conditions, -2) !== '\;') {
                $segments[$i]['conditions'] = substr($conditions, 0, -1);

                if (isset($segments[$i + 1])) {
                    $segments[$i + 1]['initialOperator'] = 'AND';
                }
            }

            if (is_string($conditions) && substr($conditions, -1) === ',' && substr($conditions, -2) !== '\,') {
                $segments[$i]['conditions'] = substr($conditions, 0, -1);

                if (isset($segments[$i + 1])) {
                    $segments[$i + 1]['initialOperator'] = 'OR';
                }
            }
        }

        return $segments;
    }

    protected function mergeOperators(array $segments): array
    {
        foreach ($segments as $segmentIndex => $segment) {
            if (is_array($segment['conditions'])) {
                $segments[$segmentIndex]['conditions'] = $this->mergeOperators($segment['conditions']);
            }

            if ($segment['conditions'] === '' && isset($segments[$segmentIndex + 1])) {
                $segments[$segmentIndex + 1]['initialOperator'] = $segment['initialOperator'];
            }

            if ($segment['conditions'] === '') {
                unset($segments[$segmentIndex]);
            }
        }

        return array_values($segments);
    }

    /**
     * @param array $segments
     * @return Expression
     */
    protected function expression(array $segments): Expression
    {
        $expression = new Expression();

        foreach ($segments as $segment) {
            $conditions = $this->extractConditions($segment);

            foreach ($conditions as $condition) {
                $expression->add($condition['operator'], $condition['condition']);
            }
        }

        return $expression;
    }

    /**
     * @param $segment
     * @return array
     */
    protected function extractConditions($segment): array
    {
        $conditions = [
            [
                'operator' => $segment['initialOperator'],
                'condition' => null,
            ]
        ];

        if (is_array($segment['conditions'])) {
            $conditions[0]['condition'] = $this->expression($segment['conditions']);

            return $conditions;
        }

        $condition = '';
        $conditionIndex = 0;
        $insideBrackets = false;

        for ($i = 0; $i < strlen($segment['conditions']); $i++) {
            $char = $segment['conditions'][$i];

            switch (true) {
                case $char === ';':
                    $conditions[$conditionIndex]['condition'] = $condition;
                    $conditions[] = ['operator' => 'AND', 'condition' => null];
                    $conditionIndex++;
                    $condition = '';
                    break;

                case $char === ',' && !$insideBrackets:
                    $conditions[$conditionIndex]['condition'] = $condition;
                    $conditions[] = ['operator' => 'OR', 'condition' => null];
                    $conditionIndex++;
                    $condition = '';
                    break;

                case $char === '(':
                    $insideBrackets = true;
                    $condition .= $char;
                    break;

                case $char === ')':
                    $insideBrackets = false;
                    $condition .= $char;
                    break;

                default:
                    $condition .= $char;
            }
        }

        if ($condition) {
            $conditions[$conditionIndex]['condition'] = $condition;
        }

        return array_map(function ($condition) {
            $condition['condition'] = Condition::fromString($condition['condition']);

            return $condition;
        }, array_filter($conditions, function ($condition) {
            return $condition['condition'] !== null;
        }));
    }
}