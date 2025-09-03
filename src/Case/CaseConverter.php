<?php

declare(strict_types=1);

namespace Ykw\Cruet\Case;

/**
 * Core case conversion algorithms ported from Rust cruet library.
 * 
 * This class contains the two main algorithms that power all case transformations:
 * - toCaseSnakeLike: for snake_case, kebab-case, SCREAMING_SNAKE_CASE
 * - toCaseCamelLike: for camelCase, PascalCase, Train-Case, etc.
 */
class CaseConverter
{
    /**
     * Options structure for camel-like case transformations
     */
    public static function createCamelOptions(
        bool $newWord = false,
        string $lastChar = ' ',
        bool $firstWord = false,
        string $injectableChar = ' ',
        bool $hasSeparator = false,
        bool $inverted = false,
        bool $concatNum = true
    ): array {
        return [
            'newWord' => $newWord,
            'lastChar' => $lastChar,
            'firstWord' => $firstWord,
            'injectableChar' => $injectableChar,
            'hasSeparator' => $hasSeparator,
            'inverted' => $inverted,
            'concatNum' => $concatNum,
        ];
    }

    /**
     * Core algorithm for snake_case-like transformations
     * Handles: snake_case, kebab-case, SCREAMING_SNAKE_CASE
     */
    public static function toCaseSnakeLike(string $convertableString, string $replaceWith, string $case): string
    {
        $firstCharacter = true;
        $result = '';
        $trimmed = self::trimRight($convertableString);
        
        $chars = mb_str_split($trimmed);
        $len = count($chars);
        
        for ($i = 0; $i < $len; $i++) {
            $char = $chars[$i];
            
            if (self::charIsSeparator($char)) {
                if (!$firstCharacter) {
                    $firstCharacter = true;
                    $result .= $replaceWith;
                }
            } elseif (self::requiresSeparator($i, $char, $firstCharacter, $convertableString)) {
                $firstCharacter = false;
                $result = self::snakeLikeWithSeparator($result, $replaceWith, $char, $case);
            } else {
                $firstCharacter = false;
                $result = self::snakeLikeNoSeparator($result, $char, $case);
            }
        }
        
        return $result;
    }

    /**
     * Core algorithm for camel-like case transformations
     * Handles: camelCase, PascalCase, Train-Case, Title Case, Sentence case
     */
    public static function toCaseCamelLike(string $convertableString, array $camelOptions): string
    {
        $newWord = $camelOptions['newWord'];
        $firstWord = $camelOptions['firstWord'];
        $lastChar = $camelOptions['lastChar'];
        $foundRealChar = false;
        $result = '';
        
        $trimmed = self::trimRight($convertableString);
        $chars = mb_str_split($trimmed);
        
        foreach ($chars as $character) {
            if (self::charIsSeparator($character) && $foundRealChar) {
                $newWord = true;
            } elseif (!$foundRealChar && self::isNotAlphanumeric($character)) {
                continue;
            } elseif (ctype_digit($character) && $camelOptions['concatNum']) {
                $foundRealChar = true;
                $newWord = true;
                $result .= $character;
            } elseif (self::lastCharLowerCurrentIsUpperOrNewWord($newWord, $lastChar, $character)) {
                $foundRealChar = true;
                $newWord = false;
                $result = self::appendOnNewWord($result, $firstWord, $character, $camelOptions);
                $firstWord = false;
            } else {
                $foundRealChar = true;
                $lastChar = $character;
                $result .= strtolower($character);
            }
        }
        
        return $result;
    }

    private static function appendOnNewWord(string $result, bool $firstWord, string $character, array $camelOptions): string
    {
        if (self::notFirstWordAndHasSeparator($firstWord, $camelOptions['hasSeparator'])) {
            $result .= $camelOptions['injectableChar'];
        }
        
        if (self::firstWordOrNotInverted($firstWord, $camelOptions['inverted'])) {
            $result .= strtoupper($character);
        } else {
            $result .= strtolower($character);
        }
        
        return $result;
    }

    private static function notFirstWordAndHasSeparator(bool $firstWord, bool $hasSeparator): bool
    {
        return $hasSeparator && !$firstWord;
    }

    private static function firstWordOrNotInverted(bool $firstWord, bool $inverted): bool
    {
        return !$inverted || $firstWord;
    }

    private static function lastCharLowerCurrentIsUpperOrNewWord(bool $newWord, string $lastChar, string $character): bool
    {
        return $newWord || ((ctype_lower($lastChar) && ctype_upper($character)) && ($lastChar !== ' '));
    }

    private static function charIsSeparator(string $character): bool
    {
        return self::isNotAlphanumeric($character);
    }

    private static function trimRight(string $convertableString): string
    {
        return rtrim($convertableString, " \t\n\r\0\x0B-_.,!@#$%^&*()[]{}|\\:;\"'<>?/+=~`");
    }

    private static function isNotAlphanumeric(string $character): bool
    {
        return !ctype_alnum($character);
    }

    private static function requiresSeparator(int $charIndex, string $char, bool $firstCharacter, string $convertableString): bool
    {
        if ($firstCharacter) {
            return false;
        }
        
        // Handle uppercase letters (original logic)
        if (self::charIsUppercase($char) && self::nextOrPreviousCharIsLowercase($convertableString, $charIndex)) {
            return true;
        }
        
        // Handle numbers: add separator before numbers when preceded by letters
        if (ctype_digit($char)) {
            $chars = mb_str_split($convertableString);
            $prevChar = ($charIndex > 0) ? $chars[$charIndex - 1] : '';
            return ctype_alpha($prevChar);
        }
        
        // Handle letters after numbers: add separator after numbers when followed by letters
        if (ctype_alpha($char)) {
            $chars = mb_str_split($convertableString);
            $prevChar = ($charIndex > 0) ? $chars[$charIndex - 1] : '';
            return ctype_digit($prevChar);
        }
        
        return false;
    }

    private static function snakeLikeNoSeparator(string $accumulator, string $currentChar, string $case): string
    {
        if ($case === 'lower') {
            return $accumulator . strtolower($currentChar);
        } else {
            return $accumulator . strtoupper($currentChar);
        }
    }

    private static function snakeLikeWithSeparator(string $accumulator, string $replaceWith, string $currentChar, string $case): string
    {
        if ($case === 'lower') {
            return $accumulator . $replaceWith . strtolower($currentChar);
        } else {
            return $accumulator . $replaceWith . strtoupper($currentChar);
        }
    }

    private static function nextOrPreviousCharIsLowercase(string $convertableString, int $charIndex): bool
    {
        $chars = mb_str_split($convertableString);
        $len = count($chars);
        
        $nextChar = ($charIndex + 1 < $len) ? $chars[$charIndex + 1] : 'A';
        $prevChar = ($charIndex > 0) ? $chars[$charIndex - 1] : 'A';
        
        return ctype_lower($nextChar) || ctype_lower($prevChar);
    }

    private static function charIsUppercase(string $testChar): bool
    {
        return $testChar === strtoupper($testChar) && ctype_alpha($testChar);
    }
}