<?php

declare(strict_types=1);

namespace Ykw\Cruet\Tests\Case;

use PHPUnit\Framework\TestCase;
use Ykw\Cruet\Inflector;

/**
 * Case transformation tests ported from Rust cruet
 * Based on tests/lib.rs in the Rust implementation
 */
class CaseTransformationTest extends TestCase
{
    // ========== CAMEL CASE TESTS ==========
    
    public function testStrToCamel(): void
    {
        $this->assertSame('fooBar', Inflector::toCamelCase('foo_bar'));
    }
    
    public function testStrToCamelNum(): void
    {
        $this->assertSame('fooBar1', Inflector::toCamelCase('foo_bar_1'));
    }
    
    public function testStrIsCamel(): void
    {
        $this->assertTrue(Inflector::isCamelCase('fooBar'));
    }
    
    public function testStrIsNotCamel(): void
    {
        $this->assertFalse(Inflector::isCamelCase('foo_bar'));
    }

    // ========== PASCAL CASE TESTS ==========
    
    public function testStrToPascal(): void
    {
        $this->assertSame('FooBar', Inflector::toPascalCase('foo_bar'));
    }
    
    public function testStrIsPascal(): void
    {
        $this->assertTrue(Inflector::isPascalCase('FooBar'));
    }
    
    public function testStrIsNotPascal(): void
    {
        $this->assertFalse(Inflector::isPascalCase('fooBar'));
    }

    // ========== SCREAMING SNAKE CASE TESTS ==========
    
    public function testStrToScreamingSnake(): void
    {
        $this->assertSame('FOO_BAR', Inflector::toScreamingSnakeCase('fooBar'));
    }
    
    public function testStrIsScreamingSnake(): void
    {
        $this->assertTrue(Inflector::isScreamingSnakeCase('FOO_BAR'));
    }
    
    public function testStrIsNotScreamingSnake(): void
    {
        $this->assertFalse(Inflector::isScreamingSnakeCase('foo_bar'));
    }

    // ========== SNAKE CASE TESTS ==========
    
    public function testStrToSnake(): void
    {
        $this->assertSame('foo_bar', Inflector::toSnakeCase('fooBar'));
    }
    
    public function testStrIsSnake(): void
    {
        $this->assertTrue(Inflector::isSnakeCase('foo_bar'));
    }
    
    public function testStrIsNotSnake(): void
    {
        $this->assertFalse(Inflector::isSnakeCase('fooBar'));
    }

    // ========== KEBAB CASE TESTS ==========
    
    public function testStrToKebab(): void
    {
        $this->assertSame('foo-bar', Inflector::toKebabCase('fooBar'));
    }
    
    public function testStrIsKebab(): void
    {
        $this->assertTrue(Inflector::isKebabCase('foo-bar'));
    }
    
    public function testStrIsNotKebab(): void
    {
        $this->assertFalse(Inflector::isKebabCase('fooBar'));
    }

    // ========== TRAIN CASE TESTS ==========
    
    public function testStrToTrain(): void
    {
        $this->assertSame('Foo-Bar', Inflector::toTrainCase('fooBar'));
    }
    
    public function testStrIsTrain(): void
    {
        $this->assertTrue(Inflector::isTrainCase('Foo-Bar'));
    }
    
    public function testStrIsNotTrain(): void
    {
        $this->assertFalse(Inflector::isTrainCase('FOO-Bar'));
    }

    // ========== SENTENCE CASE TESTS ==========
    
    public function testStrToSentence(): void
    {
        $this->assertSame('Foo bar', Inflector::toSentenceCase('fooBar'));
    }
    
    public function testStrToSentenceWithNum(): void
    {
        $this->assertSame('Foo 1 bar 2', Inflector::toSentenceCase('foo 1 bar 2'));
    }
    
    public function testStrIsSentence(): void
    {
        $this->assertTrue(Inflector::isSentenceCase('Foo bar'));
    }
    
    public function testStrIsNotSentence(): void
    {
        $this->assertFalse(Inflector::isSentenceCase('foo_bar'));
    }

    // ========== TITLE CASE TESTS ==========
    
    public function testStrToTitle(): void
    {
        $this->assertSame('Foo Bar', Inflector::toTitleCase('fooBar'));
    }
    
    public function testStrToTitleWithNum(): void
    {
        $this->assertSame('Foo 1 Bar 2', Inflector::toTitleCase('foo 1 bar 2'));
    }
    
    public function testStrIsTitle(): void
    {
        $this->assertTrue(Inflector::isTitleCase('Foo Bar'));
    }
    
    public function testStrIsNotTitle(): void
    {
        $this->assertFalse(Inflector::isTitleCase('Foo_Bar'));
    }

    // ========== CLASS CASE TESTS ==========
    
    public function testStrToClassCase(): void
    {
        $this->assertSame('Foo', Inflector::toClassCase('foo'));
    }
    
    public function testStrIsClassCase(): void
    {
        $this->assertTrue(Inflector::isClassCase('Foo'));
    }
    
    public function testStrIsNotClassCase(): void
    {
        $this->assertFalse(Inflector::isClassCase('foo'));
    }

    // ========== TABLE CASE TESTS ==========
    
    public function testStrToTable(): void
    {
        $this->assertSame('foo_bars', Inflector::toTableCase('fooBar'));
    }
    
    public function testStrIsTable(): void
    {
        $this->assertTrue(Inflector::isTableCase('foo_bars'));
    }
    
    public function testStrIsNotTable(): void
    {
        $this->assertFalse(Inflector::isTableCase('fooBars'));
    }

    // ========== COMPREHENSIVE TRANSFORMATION TESTS ==========
    
    /**
     * Data provider for comprehensive case transformation tests
     */
    public static function caseTransformationProvider(): array
    {
        return [
            // [input, camelCase, PascalCase, snake_case, SCREAMING_SNAKE, kebab-case, Train-Case, Sentence case, Title Case]
            ['foo_bar', 'fooBar', 'FooBar', 'foo_bar', 'FOO_BAR', 'foo-bar', 'Foo-Bar', 'Foo bar', 'Foo Bar'],
            ['FooBar', 'fooBar', 'FooBar', 'foo_bar', 'FOO_BAR', 'foo-bar', 'Foo-Bar', 'Foo bar', 'Foo Bar'],
            ['foo-bar', 'fooBar', 'FooBar', 'foo_bar', 'FOO_BAR', 'foo-bar', 'Foo-Bar', 'Foo bar', 'Foo Bar'],
            ['Foo Bar', 'fooBar', 'FooBar', 'foo_bar', 'FOO_BAR', 'foo-bar', 'Foo-Bar', 'Foo bar', 'Foo Bar'],
            ['foo bar', 'fooBar', 'FooBar', 'foo_bar', 'FOO_BAR', 'foo-bar', 'Foo-Bar', 'Foo bar', 'Foo Bar'],
            ['FOO_BAR', 'fooBar', 'FooBar', 'foo_bar', 'FOO_BAR', 'foo-bar', 'Foo-Bar', 'Foo bar', 'Foo Bar'],
        ];
    }
    
    /**
     * @dataProvider caseTransformationProvider
     */
    public function testComprehensiveCaseTransformations(
        string $input,
        string $expectedCamel,
        string $expectedPascal,
        string $expectedSnake,
        string $expectedScreamingSnake,
        string $expectedKebab,
        string $expectedTrain,
        string $expectedSentence,
        string $expectedTitle
    ): void {
        $this->assertSame($expectedCamel, Inflector::toCamelCase($input), "CamelCase failed for: $input");
        $this->assertSame($expectedPascal, Inflector::toPascalCase($input), "PascalCase failed for: $input");
        $this->assertSame($expectedSnake, Inflector::toSnakeCase($input), "snake_case failed for: $input");
        $this->assertSame($expectedScreamingSnake, Inflector::toScreamingSnakeCase($input), "SCREAMING_SNAKE failed for: $input");
        $this->assertSame($expectedKebab, Inflector::toKebabCase($input), "kebab-case failed for: $input");
        $this->assertSame($expectedTrain, Inflector::toTrainCase($input), "Train-Case failed for: $input");
        $this->assertSame($expectedSentence, Inflector::toSentenceCase($input), "Sentence case failed for: $input");
        $this->assertSame($expectedTitle, Inflector::toTitleCase($input), "Title Case failed for: $input");
    }

    // ========== EDGE CASE TESTS ==========
    
    public function testEmptyString(): void
    {
        $this->assertSame('', Inflector::toCamelCase(''));
        $this->assertSame('', Inflector::toSnakeCase(''));
        $this->assertSame('', Inflector::toKebabCase(''));
    }
    
    public function testSingleCharacter(): void
    {
        $this->assertSame('a', Inflector::toCamelCase('a'));
        $this->assertSame('A', Inflector::toPascalCase('a'));
        $this->assertSame('a', Inflector::toSnakeCase('A'));
    }
    
    public function testSpecialCharacters(): void
    {
        $this->assertSame('randomTextWithBadChars', Inflector::toCamelCase('Random text with *(bad) chars'));
        $this->assertSame('random_text_with_bad_chars', Inflector::toSnakeCase('Random text with *(bad) chars'));
    }
    
    public function testNumbersInString(): void
    {
        $this->assertSame('fooBar1Test2', Inflector::toCamelCase('foo_bar_1_test_2'));
        $this->assertSame('foo_bar_1_test_2', Inflector::toSnakeCase('FooBar1Test2'));
    }
}