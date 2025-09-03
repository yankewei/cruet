<?php

declare(strict_types=1);

namespace Ykw\Cruet\StringOp;

/**
 * Deconstantize functionality ported from Rust cruet library
 * Removes the final constant from a namespaced path
 * Example: "Foo::Bar" -> "Foo"
 */
class Deconstantize
{
    /**
     * Removes the rightmost segment of the passed in constant expression
     */
    public static function deconstantize(string $camelCasedWord): string
    {
        $lastColonIndex = strrpos($camelCasedWord, '::');
        
        if ($lastColonIndex === false) {
            return '';
        }
        
        return substr($camelCasedWord, 0, $lastColonIndex);
    }
}