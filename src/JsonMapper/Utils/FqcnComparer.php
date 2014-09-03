<?php

namespace JsonMapper\Utils;

/**
 * Utility for comparing two class paths making sure their formats are uniform beforehand
 */
class FqcnComparer
{
    public static function compare($classPath1, $classPath2)
    {
        return self::prependBackslashIfNeeded($classPath1) === self::prependBackslashIfNeeded($classPath2);
    }

    private static function prependBackslashIfNeeded($str)
    {
        return strpos($str, '\\') === 0 ? $str : '\\' . $str;
    }
}
