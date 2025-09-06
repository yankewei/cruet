<?php

declare(strict_types=1);

namespace Ykw\Cruet;

class SnakeLikeConverter
{
    public static function convert(
        string $convertable_string,
        string $replace_with,
        string $case,
    ): string {
        $first_character = true;
        $result = "";
        $right_trimmed = rtrim($convertable_string);

        $chars = mb_str_split($right_trimmed);
        $len = count($chars);

        for ($i = 0; $i < $len; $i++) {
            $char = $chars[$i];

            if (self::charIsSeparator($char)) {
                if (!$first_character) {
                    $first_character = true;
                    $result .= $replace_with;
                }
            } elseif (
                self::requiresSeparator(
                    $i,
                    $char,
                    $first_character,
                    $convertable_string,
                )
            ) {
                $first_character = false;
                $result = self::snakeLikeWithSeparator(
                    $result,
                    $replace_with,
                    $char,
                    $case,
                );
            } else {
                $first_character = false;
                $result = self::snakeLikeNoSeparator($result, $char, $case);
            }
        }

        return $result;
    }

    private static function requiresSeparator(
        int $charIndex,
        string $char,
        bool $firstCharacter,
        string $convertableString,
    ): bool {
        if ($firstCharacter) {
            return false;
        }

        if (
            self::charIsUppercase($char) &&
            self::nextOrPreviousCharIsLowercase($convertableString, $charIndex)
        ) {
            return true;
        }

        if (ctype_digit($char)) {
            $chars = mb_str_split($convertableString);
            $prevChar = $charIndex > 0 ? $chars[$charIndex - 1] : "";

            return ctype_alpha($prevChar);
        }

        if (ctype_alpha($char)) {
            $chars = mb_str_split($convertableString);
            $prevChar = $charIndex > 0 ? $chars[$charIndex - 1] : "";

            return ctype_digit($prevChar);
        }

        return false;
    }

    private static function snakeLikeNoSeparator(
        string $accumulator,
        string $currentChar,
        string $case,
    ): string {
        if ($case === "lower") {
            return $accumulator . strtolower($currentChar);
        }
        return $accumulator . strtoupper($currentChar);
    }

    private static function snakeLikeWithSeparator(
        string $accumulator,
        string $replaceWith,
        string $currentChar,
        string $case,
    ): string {
        if ($case === "lower") {
            return $accumulator . $replaceWith . strtolower($currentChar);
        }

        return $accumulator . $replaceWith . strtoupper($currentChar);
    }

    private static function nextOrPreviousCharIsLowercase(
        string $convertableString,
        int $charIndex,
    ): bool {
        $chars = mb_str_split($convertableString);
        $len = count($chars);

        $nextChar = $charIndex + 1 < $len ? $chars[$charIndex + 1] : "A";
        $prevChar = $charIndex > 0 ? $chars[$charIndex - 1] : "A";

        return ctype_lower($nextChar) || ctype_lower($prevChar);
    }

    private static function charIsUppercase(string $testChar): bool
    {
        return $testChar === strtoupper($testChar) && ctype_alpha($testChar);
    }

    private static function charIsSeparator(string $character): bool
    {
        return !ctype_alnum($character);
    }
}
