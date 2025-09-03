<?php

declare(strict_types=1);

namespace Ykw\Cruet\Case;

/**
 * Converts strings to and detects SCREAMING_SNAKE_CASE format
 * Example: "UserAccount" -> "USER_ACCOUNT"
 */
class ScreamingSnakeCase
{
    /**
     * Converts a string to SCREAMING_SNAKE_CASE
     */
    public static function toScreamingSnakeCase(string $nonScreamingSnakeString): string
    {
        return CaseConverter::toCaseSnakeLike($nonScreamingSnakeString, '_', 'upper');
    }

    /**
     * Determines if a string is in SCREAMING_SNAKE_CASE format
     */
    public static function isScreamingSnakeCase(string $testString): bool
    {
        return $testString === self::toScreamingSnakeCase($testString);
    }
}