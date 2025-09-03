<?php

declare(strict_types=1);

namespace Ykw\Cruet\Case;

/**
 * Converts strings to and detects snake_case format
 * Example: "UserAccount" -> "user_account"
 */
class SnakeCase
{
    /**
     * Converts a string to snake_case
     */
    public static function toSnakeCase(string $nonSnakeCaseString): string
    {
        return CaseConverter::toCaseSnakeLike($nonSnakeCaseString, '_', 'lower');
    }

    /**
     * Determines if a string is in snake_case format
     */
    public static function isSnakeCase(string $testString): bool
    {
        return $testString === self::toSnakeCase($testString);
    }
}