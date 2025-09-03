<?php

declare(strict_types=1);

namespace Ykw\Cruet\Case;

/**
 * Converts strings to and detects Train-Case format
 * Example: "user_account" -> "User-Account"
 */
class TrainCase
{
    /**
     * Converts a string to Train-Case
     */
    public static function toTrainCase(string $nonTrainString): string
    {
        $options = CaseConverter::createCamelOptions(
            newWord: true,
            lastChar: ' ',
            firstWord: true,
            injectableChar: '-',
            hasSeparator: true,
            inverted: false,
            concatNum: true
        );
        
        return CaseConverter::toCaseCamelLike($nonTrainString, $options);
    }

    /**
     * Determines if a string is in Train-Case format
     */
    public static function isTrainCase(string $testString): bool
    {
        return $testString === self::toTrainCase($testString);
    }
}