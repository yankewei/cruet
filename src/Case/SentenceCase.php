<?php

declare(strict_types=1);

namespace Ykw\Cruet\Case;

/**
 * Converts strings to and detects Sentence case format
 * Example: "user_account" -> "User account"
 */
class SentenceCase
{
    /**
     * Converts a string to Sentence case
     */
    public static function toSentenceCase(string $nonSentenceString): string
    {
        $options = CaseConverter::createCamelOptions(
            newWord: true,
            lastChar: ' ',
            firstWord: true,
            injectableChar: ' ',
            hasSeparator: true,
            inverted: true,
            concatNum: false
        );
        
        return CaseConverter::toCaseCamelLike($nonSentenceString, $options);
    }

    /**
     * Determines if a string is in Sentence case format
     */
    public static function isSentenceCase(string $testString): bool
    {
        return $testString === self::toSentenceCase($testString);
    }
}