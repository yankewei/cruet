<?php

declare(strict_types=1);

namespace Ykw\Cruet\Case;

/**
 * Converts strings to and detects kebab-case format
 * Example: "UserAccount" -> "user-account"
 */
class KebabCase
{
    /**
     * Converts a string to kebab-case
     */
    public static function toKebabCase(string $nonKebabString): string
    {
        return CaseConverter::toCaseSnakeLike($nonKebabString, '-', 'lower');
    }

    /**
     * Determines if a string is in kebab-case format
     */
    public static function isKebabCase(string $testString): bool
    {
        return $testString === self::toKebabCase($testString);
    }
}