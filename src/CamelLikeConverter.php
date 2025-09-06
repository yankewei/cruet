<?php

declare(strict_types=1);

namespace Ykw\Cruet;

class CamelLikeConverter
{
    public static function convert(string $convertableString, CamelOptions $camelOptions): string
    {
        $newWord = $camelOptions->new_word;
        $firstWord = $camelOptions->first_word;
        $lastChar = $camelOptions->last_char;
        $foundRealChar = false;
        $result = '';

        $trimmed = self::trimRight($convertableString);
        $chars = mb_str_split($trimmed);

        foreach ($chars as $character) {
            if (self::charIsSeparator($character) && $foundRealChar) {
                $newWord = true;
            } elseif (!$foundRealChar && self::isNotAlphanumeric($character)) {
                continue;
            } elseif (ctype_digit($character) && $camelOptions->concat_num) {
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

    private static function appendOnNewWord(
        string $result,
        bool $firstWord,
        string $character,
        CamelOptions $camelOptions,
    ): string {
        if (self::notFirstWordAndHasSeparator($firstWord, $camelOptions->has_separator)) {
            $result .= $camelOptions->injectable_char;
        }

        if (self::firstWordOrNotInverted($firstWord, $camelOptions->inverted)) {
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

    private static function lastCharLowerCurrentIsUpperOrNewWord(
        bool $newWord,
        string $lastChar,
        string $character,
    ): bool {
        return $newWord || ctype_lower($lastChar) && ctype_upper($character) && $lastChar !== ' ';
    }

    private static function charIsSeparator(string $character): bool
    {
        return self::isNotAlphanumeric($character);
    }

    private static function trimRight(string $convertableString): string
    {
        return rtrim(
            string: $convertableString,
            characters: " \t\n\r\0\x0B-_.,!@#$%^&*()[]{}|\\:;\"'<>?/+=~`",
        );
    }

    private static function isNotAlphanumeric(string $character): bool
    {
        return !ctype_alnum($character);
    }
}
