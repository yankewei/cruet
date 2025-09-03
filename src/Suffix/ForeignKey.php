<?php

declare(strict_types=1);

namespace Ykw\Cruet\Suffix;

use Ykw\Cruet\Case\SnakeCase;
use Ykw\Cruet\StringOp\Demodulize;

/**
 * Foreign key generation functionality ported from Rust cruet library
 * Converts class names to foreign key format
 * Example: "UserAccount" -> "user_account_id"
 */
class ForeignKey
{
    /**
     * Converts a string to foreign key format
     */
    public static function toForeignKey(string $className): string
    {
        $demodulized = Demodulize::demodulize($className);
        $snakeCase = SnakeCase::toSnakeCase($demodulized);
        return $snakeCase . '_id';
    }

    /**
     * Determines if a string is in foreign key format
     */
    public static function isForeignKey(string $testString): bool
    {
        return str_ends_with($testString, '_id');
    }
}