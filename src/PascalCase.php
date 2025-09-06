<?php

declare(strict_types=1);

namespace Ykw\Cruet;

/**
 * Converts strings to and detects PascalCase format
 * Example: "user_account" -> "UserAccount"
 */
class PascalCase
{
    /**
     * Converts a string to PascalCase
     */
    public static function toPascalCase(string $nonPascalizedString): string
    {
        $options = CaseConverter::createCamelOptions(
            newWord: true,
            lastChar: ' ',
            firstWord: false,
            injectableChar: ' ',
            hasSeparator: false,
            inverted: false,
            concatNum: true,
        );

        return CaseConverter::toCaseCamelLike($nonPascalizedString, $options);
    }

    /**
     * Determines if a string is in PascalCase format
     */
    public static function isPascalCase(string $testString): bool
    {
        return self::toPascalCase($testString) === $testString;
    }
}
