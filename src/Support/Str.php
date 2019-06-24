<?php

namespace Oilstone\RsqlParser\Support;

use Exception;
use Illuminate\Support\Str as BaseStr;

/**
 * Class Str
 * @package Oilstone\RsqlParser\Support
 */
class Str extends BaseStr
{
    /**
     * @var array
     */
    protected static $escaped = ['\\\\', '\(', '\)', '\,', '\;', '\\\'', '\"'];

    /**
     * @var array
     */
    protected static $encoded = ['{~bs~}', '{~ob~}', '{~cb~}', '{~cm~}', '{~sc~}', '{~ap~}', '{~dq~}'];

    /**
     * @var array
     */
    protected static $unescaped = ['\\', '(', ')', ',', ';', '\'', '"'];

    /**
     * @param string $string
     * @param int $openingBracketPosition
     * @return int
     * @throws Exception
     */
    public static function findClosingBracket(string $string, int $openingBracketPosition): int
    {
        $nextClosingBracket = static::findUnescapedInstance($string, ')', $openingBracketPosition + 1);
        $nextOpeningBracket = static::findUnescapedInstance($string, '(', $openingBracketPosition + 1);

        while ($nextOpeningBracket !== false && $nextOpeningBracket < $nextClosingBracket) {
            $nextOpeningBracket = static::findUnescapedInstance($string, '(', $nextOpeningBracket + 1);
            $nextClosingBracket = static::findUnescapedInstance($string, ')', $nextClosingBracket + 1);
        }

        if ($nextClosingBracket === false) {
            throw new Exception('Unmatched bracket at position ' . $openingBracketPosition);
        }

        return $nextClosingBracket;
    }

    /**
     * @param string $haystack
     * @param string $needle
     * @param int $offset
     * @param array|string $escapeCharacters
     * @return int|bool
     */
    public static function findUnescapedInstance(string $haystack, string $needle, int $offset = 0, $escapeCharacters = ['\\'])
    {
        if (!is_array($escapeCharacters)) {
            $escapeCharacters = [$escapeCharacters];
        }

        $escapeCharacters = array_map(function ($escapeCharacter) use ($needle) {
            return $escapeCharacter . $needle;
        }, $escapeCharacters);

        return strpos(str_replace($escapeCharacters, '00', $haystack), $needle, $offset);
    }

    /**
     * @param $value
     * @return array|mixed
     */
    public static function escape($value)
    {
        if (is_array($value)) {
            return array_map(function ($string) {
                return static::unescape($string);
            }, $value);
        }

        $value = str_replace(static::$escaped, static::$encoded, $value);

        return $value;
    }

    /**
     * @param $value
     * @return array|mixed
     */
    public static function unescape($value)
    {
        if (is_array($value)) {
            return array_map(function ($string) {
                return static::unescape($string);
            }, $value);
        }

        $value = str_replace(static::$encoded, static::$unescaped, $value);

        return $value;
    }
}