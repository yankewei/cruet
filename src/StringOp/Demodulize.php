<?php

declare(strict_types=1);

namespace Ykw\Cruet\StringOp;

/**
 * Demodulize functionality ported from Rust cruet library
 * Removes the module part from a namespaced class name
 * Example: "Foo::Bar" -> "Bar"
 */
class Demodulize
{
    /**
     * Removes the module part from the expression in the string
     */
    public static function demodulize(string $camelCasedWord): string
    {
        $lastColonIndex = strrpos($camelCasedWord, '::');
        
        if ($lastColonIndex === false) {
            return $camelCasedWord;
        }
        
        return substr($camelCasedWord, $lastColonIndex + 2);
    }
}