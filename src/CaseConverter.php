<?php

declare(strict_types=1);

namespace Ykw\Cruet;

/**
 * Simplified CaseConverter that delegates to specialized converters
 */
class CaseConverter
{
    public static function createCamelOptions(
        bool $newWord = false,
        string $lastChar = ' ',
        bool $firstWord = false,
        string $injectableChar = ' ',
        bool $hasSeparator = false,
        bool $inverted = false,
        bool $concatNum = true,
    ): CamelOptions {
        return new CamelOptions(
            new_word: $newWord,
            last_char: $lastChar,
            first_word: $firstWord,
            injectable_char: $injectableChar,
            has_separator: $hasSeparator,
            inverted: $inverted,
            concat_num: $concatNum,
        );
    }

    public static function toCaseSnakeLike(string $convertable_string, string $replace_with, string $case): string
    {
        return SnakeLikeConverter::convert($convertable_string, $replace_with, $case);
    }

    public static function toCaseCamelLike(string $convertableString, CamelOptions $camelOptions): string
    {
        return CamelLikeConverter::convert($convertableString, $camelOptions);
    }
}
