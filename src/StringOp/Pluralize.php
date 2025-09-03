<?php

declare(strict_types=1);

namespace Ykw\Cruet\StringOp;

/**
 * Pluralization functionality ported from Rust cruet library
 */
class Pluralize
{
    /**
     * Pluralization rules ported from Rust cruet
     * Each rule is a [pattern, replacement] pair
     */
    private const RULES = [
        ['/(\w*)s$/', '$1s'],
        ['/(\w*([^aeiou]ese))$/', '$1'],
        ['/(\w*(ax|test))is$/', '$1es'],
        ['/(\w*(alias|[^aou]us|tlas|gas|ris))$/', '$1es'],
        ['/(\w*(e[mn]u))s?$/', '$1s'],
        ['/(\w*([^l]ias|[aeiou]las|[emjzr]as|[iu]am))$/', '$1'],
        ['/(\w*(alumn|syllab|octop|vir|radi|nucle|fung|cact|stimul|termin|bacill|foc|uter|loc|strat))(?:us|i)$/', '$1i'],
        ['/(\w*(alumn|alg|vertebr))(?:a|ae)$/', '$1ae'],
        ['/(\w*(seraph|cherub))(?:im)?$/', '$1im'],
        ['/(\w*(her|at|gr|ech))o$/', '$1oes'],
        ['/(\w*(agend|addend|millenni|dat|extrem|bacteri|desiderat|strat|candelabr|errat|ov|symposi|curricul|automat|quor))(?:a|um)$/', '$1a'],
        ['/(\w*(apheli|hyperbat|periheli|asyndet|noumen|phenomen|criteri|organ|prolegomen|hedr|automat))(?:a|on)$/', '$1a'],
        ['/(\w*)sis$/', '$1ses'],
        ['/(\w*(kni|wi|li))fe$/', '$1ves'],
        ['/(\w*(ar|l|ea|eo|oa|hoo))f$/', '$1ves'],
        ['/(\w*([^aeiouy]|qu))y$/', '$1ies'],
        ['/(\w*([^ch][ieo][ln]))ey$/', '$1ies'],
        ['/(\w*(x|ch|ss|sh|zz)es)$/', '$1'],
        ['/(\w*(x|ch|ss|sh|zz))$/', '$1es'],
        ['/(\w*(matr|cod|mur|sil|vert|ind|append))(?:ix|ex)$/', '$1ices'],
        ['/(\w*(m|l))(?:ice|ouse)$/', '$1ice'],
        ['/(\w*(pe))(?:rson|ople)$/', '$1ople'],
        ['/(\w*(child))(?:ren)?$/', '$1ren'],
        ['/(\w*eaux)$/', '$1']
    ];

    /**
     * Special cases that don't follow regular rules
     */
    private const SPECIAL_CASES = [
        'ox' => 'oxen',
        'man' => 'men',
        'woman' => 'women',
        'die' => 'dice',
        'yes' => 'yeses',
        'foot' => 'feet',
        'eave' => 'eaves',
        'goose' => 'geese',
        'tooth' => 'teeth',
        'quiz' => 'quizzes'
    ];

    /**
     * Converts a string to its plural form
     */
    public static function toPlural(string $nonPluralString): string
    {
        // Handle empty string
        if (empty($nonPluralString)) {
            return $nonPluralString;
        }
        
        // Check if it's an uncountable word
        if (in_array($nonPluralString, Constants::UNCOUNTABLE_WORDS, true)) {
            return $nonPluralString;
        }

        // Check special cases
        if (array_key_exists($nonPluralString, self::SPECIAL_CASES)) {
            return self::SPECIAL_CASES[$nonPluralString];
        }

        // Apply rules in reverse order (like Rust implementation)
        foreach (array_reverse(self::RULES) as [$pattern, $replacement]) {
            if (preg_match($pattern, $nonPluralString, $matches)) {
                return preg_replace($pattern, $replacement, $nonPluralString);
            }
        }

        // Default rule: just add 's'
        return $nonPluralString . 's';
    }
}