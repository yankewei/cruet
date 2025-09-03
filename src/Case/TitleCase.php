<?php

declare(strict_types=1);

namespace Ykw\Cruet\Case;

/**
 * Converts strings to and detects Title Case format
 * Example: "user_account" -> "User Account"
 */
class TitleCase
{
    /**
     * Converts a string to Title Case
     */
    public static function toTitleCase(string $nonTitleString): string
    {
        $options = CaseConverter::createCamelOptions(
            newWord: true,
            lastChar: ' ',
            firstWord: true,
            injectableChar: ' ',
            hasSeparator: true,
            inverted: false,
            concatNum: false
        );
        
        return CaseConverter::toCaseCamelLike($nonTitleString, $options);
    }

    /**
     * Determines if a string is in Title Case format
     */
    public static function isTitleCase(string $testString): bool
    {
        return $testString === self::toTitleCase($testString);
    }
}