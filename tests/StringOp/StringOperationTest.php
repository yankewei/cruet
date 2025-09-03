<?php

declare(strict_types=1);

namespace Ykw\Cruet\Tests\StringOp;

use PHPUnit\Framework\TestCase;
use Ykw\Cruet\Inflector;

/**
 * String operation tests ported from Rust cruet
 * Based on tests/lib.rs in the Rust implementation
 */
class StringOperationTest extends TestCase
{
    // ========== PLURALIZATION TESTS ==========
    
    public function testStrPluralize(): void
    {
        $this->assertSame('crates', Inflector::toPlural('crate'));
    }
    
    public function testStrSingular(): void
    {
        $this->assertSame('crate', Inflector::toSingular('crates'));
    }

    // ========== DEMODULIZE TESTS ==========
    
    public function testStrDemodulize(): void
    {
        $this->assertSame('Bar', Inflector::demodulize('Foo::Bar'));
    }

    // ========== DECONSTANTIZE TESTS ==========
    
    public function testStrDeconstantize(): void
    {
        $this->assertSame('Foo', Inflector::deconstantize('Foo::Bar'));
    }

    // ========== COMPREHENSIVE PLURALIZATION TESTS ==========
    
    /**
     * Data provider for pluralization tests from Rust implementation
     */
    public static function pluralizationProvider(): array
    {
        return [
            // Regular plurals
            ['cat', 'cats'],
            ['dog', 'dogs'],
            ['house', 'houses'],
            ['tree', 'trees'],
            
            // Special cases from Rust implementation
            ['ox', 'oxen'],
            ['man', 'men'],
            ['woman', 'women'],
            ['die', 'dice'],
            ['foot', 'feet'],
            ['tooth', 'teeth'],
            ['goose', 'geese'],
            ['child', 'children'],
            ['quiz', 'quizzes'],
            
            // -y endings
            ['city', 'cities'],
            ['party', 'parties'],
            ['lady', 'ladies'],
            ['baby', 'babies'],
            
            // -f/-fe endings  
            ['knife', 'knives'],
            ['life', 'lives'],
            ['wife', 'wives'],
            ['leaf', 'leaves'],
            ['calf', 'calves'],
            ['half', 'halves'],
            
            // -s, -sh, -ch, -x, -z endings
            ['bus', 'buses'],
            ['wish', 'wishes'],
            ['church', 'churches'],
            ['box', 'boxes'],
            ['buzz', 'buzzes'],
            
            // Irregular Latin/Greek endings
            ['analysis', 'analyses'],
            ['basis', 'bases'],
            ['crisis', 'crises'],
            ['diagnosis', 'diagnoses'],
            
            // Words ending in -o
            ['hero', 'heroes'],
            ['potato', 'potatoes'],
            ['echo', 'echoes'],
            
            // Uncountable words (should remain the same)
            ['sheep', 'sheep'],
            ['deer', 'deer'],
            ['fish', 'fish'],
            ['advice', 'advice'],
            ['information', 'information'],
            ['equipment', 'equipment'],
            ['water', 'water'],
            ['music', 'music'],
        ];
    }
    
    /**
     * @dataProvider pluralizationProvider
     */
    public function testPluralization(string $singular, string $expectedPlural): void
    {
        $actualPlural = Inflector::toPlural($singular);
        $this->assertSame($expectedPlural, $actualPlural, "Pluralization failed for: $singular");
        
        // Test round-trip: plural -> singular should return original
        $backToSingular = Inflector::toSingular($actualPlural);
        $this->assertSame($singular, $backToSingular, "Round-trip failed for: $singular -> $actualPlural -> $backToSingular");
    }

    // ========== COMPREHENSIVE SINGULARIZATION TESTS ==========
    
    /**
     * Data provider for singularization tests
     */
    public static function singularizationProvider(): array
    {
        return [
            // Regular singulars
            ['cats', 'cat'],
            ['dogs', 'dog'],
            ['houses', 'house'],
            ['trees', 'tree'],
            
            // Special cases
            ['oxen', 'ox'],
            ['men', 'man'], 
            ['women', 'woman'],
            ['dice', 'die'],
            ['feet', 'foot'],
            ['teeth', 'tooth'],
            ['geese', 'goose'],
            ['children', 'child'],
            ['quizzes', 'quiz'],
            
            // -ies endings
            ['cities', 'city'],
            ['parties', 'party'],
            ['ladies', 'lady'],
            ['babies', 'baby'],
            
            // -ves endings
            ['knives', 'knife'],
            ['lives', 'life'],
            ['wives', 'wife'],
            ['leaves', 'leaf'],
            ['calves', 'calf'],
            ['halves', 'half'],
            
            // -es endings
            ['buses', 'bus'],
            ['wishes', 'wish'],
            ['churches', 'church'],
            ['boxes', 'box'],
            ['buzzes', 'buzz'],
            
            // Latin/Greek endings
            ['analyses', 'analysis'],
            ['bases', 'basis'],
            ['crises', 'crisis'],
            ['diagnoses', 'diagnosis'],
            
            // -oes endings
            ['heroes', 'hero'],
            ['potatoes', 'potato'],
            ['echoes', 'echo'],
        ];
    }
    
    /**
     * @dataProvider singularizationProvider
     */
    public function testSingularization(string $plural, string $expectedSingular): void
    {
        $this->assertSame($expectedSingular, Inflector::toSingular($plural), "Singularization failed for: $plural");
    }

    // ========== NAMESPACE OPERATION TESTS ==========
    
    /**
     * Data provider for demodulize tests
     */
    public static function demodulizeProvider(): array
    {
        return [
            ['Foo::Bar', 'Bar'],
            ['App::Models::User', 'User'],
            ['Foo::Bar::Baz::Qux', 'Qux'],
            ['MyClass', 'MyClass'], // No namespace
            ['', ''], // Empty string
        ];
    }
    
    /**
     * @dataProvider demodulizeProvider
     */
    public function testDemodulize(string $input, string $expected): void
    {
        $this->assertSame($expected, Inflector::demodulize($input), "Demodulize failed for: $input");
    }
    
    /**
     * Data provider for deconstantize tests
     */
    public static function deconstantizeProvider(): array
    {
        return [
            ['Foo::Bar', 'Foo'],
            ['App::Models::User', 'App::Models'],
            ['Foo::Bar::Baz::Qux', 'Foo::Bar::Baz'],
            ['MyClass', ''], // No namespace
            ['', ''], // Empty string
        ];
    }
    
    /**
     * @dataProvider deconstantizeProvider
     */
    public function testDeconstantize(string $input, string $expected): void
    {
        $this->assertSame($expected, Inflector::deconstantize($input), "Deconstantize failed for: $input");
    }

    // ========== EDGE CASES ==========
    
    public function testEmptyStringOperations(): void
    {
        $this->assertSame('', Inflector::toPlural(''));
        $this->assertSame('', Inflector::toSingular(''));
        $this->assertSame('', Inflector::demodulize(''));
        $this->assertSame('', Inflector::deconstantize(''));
    }
    
    public function testAlreadyPlural(): void
    {
        // Test that already plural words remain plural
        $this->assertSame('boxes', Inflector::toPlural('boxes'));
        $this->assertSame('children', Inflector::toPlural('children'));
    }
    
    public function testAlreadySingular(): void
    {
        // Test that already singular words remain singular when singularized
        $this->assertSame('box', Inflector::toSingular('box'));
        $this->assertSame('child', Inflector::toSingular('child'));
    }

    // ========== COMPLEX WORD TESTS ==========
    
    public function testComplexWords(): void
    {
        // Test compound words and complex cases
        $this->assertSame('mouse_traps', Inflector::toPlural('mouse_trap'));
        $this->assertSame('UserAccounts', Inflector::toClassCase('user_accounts'));
        $this->assertSame('user_accounts', Inflector::toTableCase('UserAccount'));
    }
}