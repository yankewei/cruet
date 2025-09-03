<?php

declare(strict_types=1);

namespace Ykw\Cruet\Number;

/**
 * Deordinalization functionality ported from Rust cruet library
 * Removes ordinal suffixes from numbers (1st -> 1, 2nd -> 2, etc.)
 */
class Deordinalize
{
    /**
     * Removes the ordinal suffix from a string
     */
    public static function deordinalize(string $ordinalizedString): string
    {
        // Remove ordinal suffixes: st, nd, rd, th
        return preg_replace('/(st|nd|rd|th)$/', '', $ordinalizedString);
    }
}