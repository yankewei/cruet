<?php

declare(strict_types=1);

namespace Ykw\Cruet\Number;

/**
 * Ordinalization functionality ported from Rust cruet library
 * Converts numbers to ordinal form (1st, 2nd, 3rd, etc.)
 */
class Ordinalize
{
    /**
     * Converts a number string to its ordinal form
     */
    public static function ordinalize(string $nonOrdinalizedString): string
    {
        if (empty($nonOrdinalizedString)) {
            return '';
        }
        
        $chars = str_split($nonOrdinalizedString);
        $lastNumber = end($chars);
        
        if (!$lastNumber || self::isOrdinalizable($lastNumber)) {
            return $nonOrdinalizedString;
        }
        
        if (count($chars) > 1) {
            if (self::secondLastNumberIsOne($chars)) {
                return $nonOrdinalizedString . 'th';
            } elseif (self::stringContainsDecimal($nonOrdinalizedString)) {
                return $nonOrdinalizedString;
            }
        }
        
        return match ($lastNumber) {
            '1' => $nonOrdinalizedString . 'st',
            '2' => $nonOrdinalizedString . 'nd',
            '3' => $nonOrdinalizedString . 'rd',
            default => $nonOrdinalizedString . 'th',
        };
    }

    private static function isOrdinalizable(string $lastNumber): bool
    {
        return !ctype_digit($lastNumber);
    }

    private static function secondLastNumberIsOne(array $chars): bool
    {
        $secondLastNumber = $chars[count($chars) - 2];
        return $secondLastNumber === '1';
    }

    private static function stringContainsDecimal(string $nonOrdinalizedString): bool
    {
        return str_contains($nonOrdinalizedString, '.');
    }
}