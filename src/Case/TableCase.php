<?php

declare(strict_types=1);

namespace Ykw\Cruet\Case;

use Ykw\Cruet\StringOp\Pluralize;

/**
 * Converts strings to and detects table_case format (snake_case + Pluralize)
 * Example: "UserAccount" -> "user_accounts"
 */
class TableCase
{
    /**
     * Converts a string to table_case
     */
    public static function toTableCase(string $nonTableString): string
    {
        $snakeCase = SnakeCase::toSnakeCase($nonTableString);
        return Pluralize::toPlural($snakeCase);
    }

    /**
     * Determines if a string is in table_case format
     */
    public static function isTableCase(string $testString): bool
    {
        return $testString === self::toTableCase($testString);
    }
}