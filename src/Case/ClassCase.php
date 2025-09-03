<?php

declare(strict_types=1);

namespace Ykw\Cruet\Case;

use Ykw\Cruet\StringOp\Singularize;

/**
 * Converts strings to and detects ClassCase format (PascalCase + Singularize)
 * Example: "user_accounts" -> "UserAccount"
 */
class ClassCase
{
    /**
     * Converts a string to ClassCase
     */
    public static function toClassCase(string $nonClassString): string
    {
        $singular = Singularize::toSingular($nonClassString);
        return PascalCase::toPascalCase($singular);
    }

    /**
     * Determines if a string is in ClassCase format
     */
    public static function isClassCase(string $testString): bool
    {
        return $testString === self::toClassCase($testString);
    }
}