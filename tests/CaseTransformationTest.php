<?php

declare(strict_types=1);

namespace Ykw\Cruet\Tests;

use PHPUnit\Framework\TestCase;
use Ykw\Cruet\Inflector;

class CaseTransformationTest extends TestCase
{
    // ========== CAMEL CASE TESTS ==========

    public function testStrToCamelCase(): void
    {
        static::assertSame('fooBar', Inflector::toCamelCase('foo_bar'));
        static::assertSame('fooBar', Inflector::toCamelCase('Foo Bar'));
        static::assertSame('fooBar', Inflector::toCamelCase('foo-bar'));
        static::assertSame('fooBar', Inflector::toCamelCase('FOO_BAR'));
    }

    public function testStrIsCamelCase(): void
    {
        static::assertTrue(Inflector::isCamelCase('fooBar'));
        static::assertTrue(Inflector::isCamelCase('foo'));
    }

    public function testStrIsNotCamelCase(): void
    {
        static::assertFalse(Inflector::isCamelCase('FooBar'));
        static::assertFalse(Inflector::isCamelCase('foo_bar'));
        static::assertFalse(Inflector::isCamelCase('foo-bar'));
    }

    // ========== PASCAL CASE TESTS ==========

    public function testStrToPascalCase(): void
    {
        static::assertSame('FooBar', Inflector::toPascalCase('foo_bar'));
        static::assertSame('FooBar', Inflector::toPascalCase('fooBar'));
        static::assertSame('FooBar', Inflector::toPascalCase('foo-bar'));
        static::assertSame('FooBar', Inflector::toPascalCase('FOO_BAR'));
    }

    public function testStrIsPascalCase(): void
    {
        static::assertTrue(Inflector::isPascalCase('FooBar'));
        static::assertTrue(Inflector::isPascalCase('Foo'));
    }

    public function testStrIsNotPascalCase(): void
    {
        static::assertFalse(Inflector::isPascalCase('fooBar'));
        static::assertFalse(Inflector::isPascalCase('foo_bar'));
        static::assertFalse(Inflector::isPascalCase('foo-bar'));
    }

    // ========== SNAKE CASE TESTS ==========

    public function testStrToSnakeCase(): void
    {
        static::assertSame('foo_bar', Inflector::toSnakeCase('fooBar'));
        static::assertSame('foo_bar', Inflector::toSnakeCase('FooBar'));
        static::assertSame('foo_bar', Inflector::toSnakeCase('foo-bar'));
        static::assertSame('foo_bar', Inflector::toSnakeCase('FOO_BAR'));
    }

    public function testStrIsSnakeCase(): void
    {
        static::assertTrue(Inflector::isSnakeCase('foo_bar'));
        static::assertTrue(Inflector::isSnakeCase('foo'));
    }

    public function testStrIsNotSnakeCase(): void
    {
        static::assertFalse(Inflector::isSnakeCase('fooBar'));
        static::assertFalse(Inflector::isSnakeCase('FooBar'));
        static::assertFalse(Inflector::isSnakeCase('foo-bar'));
    }

    // ========== SCREAMING SNAKE CASE TESTS ==========

    public function testStrToScreamingSnakeCase(): void
    {
        static::assertSame('FOO_BAR', Inflector::toScreamingSnakeCase('fooBar'));
        static::assertSame('FOO_BAR', Inflector::toScreamingSnakeCase('FooBar'));
        static::assertSame('FOO_BAR', Inflector::toScreamingSnakeCase('foo-bar'));
        static::assertSame('FOO_BAR', Inflector::toScreamingSnakeCase('foo_bar'));
    }

    public function testStrIsScreamingSnakeCase(): void
    {
        static::assertTrue(Inflector::isScreamingSnakeCase('FOO_BAR'));
        static::assertTrue(Inflector::isScreamingSnakeCase('FOO'));
    }

    public function testStrIsNotScreamingSnakeCase(): void
    {
        static::assertFalse(Inflector::isScreamingSnakeCase('fooBar'));
        static::assertFalse(Inflector::isScreamingSnakeCase('FooBar'));
        static::assertFalse(Inflector::isScreamingSnakeCase('foo_bar'));
    }

    // ========== KEBAB CASE TESTS ==========

    public function testStrToKebabCase(): void
    {
        static::assertSame('foo-bar', Inflector::toKebabCase('fooBar'));
        static::assertSame('foo-bar', Inflector::toKebabCase('FooBar'));
        static::assertSame('foo-bar', Inflector::toKebabCase('foo_bar'));
        static::assertSame('foo-bar', Inflector::toKebabCase('FOO_BAR'));
    }

    public function testStrIsKebabCase(): void
    {
        static::assertTrue(Inflector::isKebabCase('foo-bar'));
        static::assertTrue(Inflector::isKebabCase('foo'));
    }

    public function testStrIsNotKebabCase(): void
    {
        static::assertFalse(Inflector::isKebabCase('fooBar'));
        static::assertFalse(Inflector::isKebabCase('FooBar'));
        static::assertFalse(Inflector::isKebabCase('foo_bar'));
    }

    // ========== FLUENT INTERFACE TESTS ==========

    public function testFluentInterface(): void
    {
        $result = Inflector::convert('foo_bar')->camelCase()->get();
        static::assertSame('fooBar', $result);

        $result = Inflector::convert('fooBar')->snakeCase()->get();
        static::assertSame('foo_bar', $result);

        $result = Inflector::convert('foo_bar')
            ->pascalCase()
            ->kebabCase()
            ->get();
        static::assertSame('foo-bar', $result);
    }

    // ========== SPECIAL CHARACTER HANDLING ==========

    public function testSpecialCharacterHandling(): void
    {
        static::assertSame('randomTextWithBadChars', Inflector::toCamelCase('Random text with *(bad) chars'));
        static::assertSame('random_text_with_bad_chars', Inflector::toSnakeCase('Random text with *(bad) chars'));
    }

    public function testNumbersInString(): void
    {
        static::assertSame('fooBar1Test2', Inflector::toCamelCase('foo_bar_1_test_2'));
        static::assertSame('foo_bar_1_test_2', Inflector::toSnakeCase('FooBar1Test2'));
    }
}
