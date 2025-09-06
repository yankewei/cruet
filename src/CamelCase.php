<?php

declare(strict_types=1);

namespace Ykw\Cruet;

/**
 * Converts strings to and detects camelCase format
 * Example: "user_account" -> "userAccount"
 */
class CamelCase
{
    /**
     * Converts a string to camelCase
     */
    public static function toCamelCase(string $nonCamelizedString): string
    {
        $options = CaseConverter::createCamelOptions(
            newWord: false,
            lastChar: ' ',
            firstWord: false,
            injectableChar: ' ',
            hasSeparator: false,
            inverted: false,
            concatNum: true,
        );

        return CaseConverter::toCaseCamelLike($nonCamelizedString, $options);
    }

    /**
     * Determines if a string is in camelCase format
     */
    public static function isCamelCase(string $testString): bool
    {
        return self::toCamelCase($testString) === $testString;
    }
}
