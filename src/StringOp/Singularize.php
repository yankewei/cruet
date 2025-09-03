<?php

declare(strict_types=1);

namespace Ykw\Cruet\StringOp;

/**
 * Singularization functionality ported from Rust cruet library
 */
class Singularize
{
    /**
     * Singularization rules ported from Rust cruet
     * Each rule is a [pattern, replacement] pair
     */
    private const RULES = [
        ['/(\w*)s$/', '$1'],
        ['/(\w*(ss))$/', '$1'],
        ['/(\w*(n))ews$/', '$1ews'],
        ['/(\w*(o))es$/', '$1'],
        ['/(\w*([ti]))a$/', '$1um'],
        ['/(\w*((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he))(sis|ses)$/', '$1sis'],
        ['/(^analy)(sis|ses)$/', '$1sis'],
        ['/(\w*([^f]))ves$/', '$1fe'],
        ['/(\w*(af|if|ef))ves$/', '$1'],
        ['/(\w*([lr]))ves$/', '$1f'],
        ['/(\w*(ea))ves$/', '$1f'],
        ['/(\w*(hive))s$/', '$1'],
        ['/(\w*(tive))s$/', '$1'],
        ['/(\w*([^aeiouy]|qu))ies$/', '$1y'],
        ['/(\w*(s))eries$/', '$1eries'],
        ['/(\w*(m))ovies$/', '$1ovie'],
        ['/(\w*(x|ch|ss|sh|zz))es$/', '$1'],
        ['/(\w*(m|l))ice$/', '$1ouse'],
        ['/(\w*(bus))(es)?$/', '$1'],
        ['/(\w*(shoe))s$/', '$1'],
        ['/(\w*(cris|test))(is|es)$/', '$1is'],
        ['/^(a)x[ie]s$/', '$1xis'],
        ['/(\w*(octop|vir))(us|i)$/', '$1us'],
        ['/(\w*(alias|status))(es)?$/', '$1'],
        ['/^(ox)en/', '$1'],
        ['/(\w*(vert|ind))ices$/', '$1ex'],
        ['/(\w*(matr))ices$/', '$1ix'],
        ['/(\w*(quiz))zes$/', '$1'],
        ['/(\w*(database))s$/', '$1']
    ];

    /**
     * Special cases that don't follow regular rules (reverse of pluralize special cases)
     */
    private const SPECIAL_CASES = [
        'oxen' => 'ox',
        'boxes' => 'box',
        'men' => 'man',
        'women' => 'woman',
        'dice' => 'die',
        'yeses' => 'yes',
        'feet' => 'foot',
        'eaves' => 'eave',
        'geese' => 'goose',
        'teeth' => 'tooth',
        'quizzes' => 'quiz',
        'children' => 'child'
    ];

    /**
     * Converts a string to its singular form
     */
    public static function toSingular(string $nonSingularString): string
    {
        // Check if it's an uncountable word
        if (in_array($nonSingularString, Constants::UNCOUNTABLE_WORDS, true)) {
            return $nonSingularString;
        }

        // Check special cases
        if (array_key_exists($nonSingularString, self::SPECIAL_CASES)) {
            return self::SPECIAL_CASES[$nonSingularString];
        }

        // Apply rules in reverse order (like Rust implementation)
        foreach (array_reverse(self::RULES) as [$pattern, $replacement]) {
            if (preg_match($pattern, $nonSingularString)) {
                return preg_replace($pattern, $replacement, $nonSingularString);
            }
        }

        // If no rule matched, return as is
        return $nonSingularString;
    }
}