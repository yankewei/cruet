<?php

declare(strict_types=1);

namespace Ykw\Cruet;

use Ykw\Cruet\CamelCase;
use Ykw\Cruet\KebabCase;
use Ykw\Cruet\PascalCase;
use Ykw\Cruet\ScreamingSnakeCase;
use Ykw\Cruet\SnakeCase;

/**
 * Main Inflector class - unified entry point for case transformations
 * Provides both static methods and fluent interface
 */
class Inflector
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * Static factory method for fluent interface
     */
    public static function convert(string $value): self
    {
        return new self($value);
    }

    /**
     * Get the current value (end of fluent chain)
     */
    public function get(): string
    {
        return $this->value;
    }

    public static function toCamelCase(string $string): string
    {
        return CamelCase::toCamelCase($string);
    }

    public static function isCamelCase(string $string): bool
    {
        return CamelCase::isCamelCase($string);
    }

    public static function toPascalCase(string $string): string
    {
        return PascalCase::toPascalCase($string);
    }

    public static function isPascalCase(string $string): bool
    {
        return PascalCase::isPascalCase($string);
    }

    public static function toSnakeCase(string $string): string
    {
        return SnakeCase::toSnakeCase($string);
    }

    public static function isSnakeCase(string $string): bool
    {
        return SnakeCase::isSnakeCase($string);
    }

    public static function toScreamingSnakeCase(string $string): string
    {
        return ScreamingSnakeCase::toScreamingSnakeCase($string);
    }

    public static function isScreamingSnakeCase(string $string): bool
    {
        return ScreamingSnakeCase::isScreamingSnakeCase($string);
    }

    public static function toKebabCase(string $string): string
    {
        return KebabCase::toKebabCase($string);
    }

    public static function isKebabCase(string $string): bool
    {
        return KebabCase::isKebabCase($string);
    }

    public function camelCase(): self
    {
        $this->value = CamelCase::toCamelCase($this->value);

        return $this;
    }

    public function pascalCase(): self
    {
        $this->value = PascalCase::toPascalCase($this->value);

        return $this;
    }

    public function snakeCase(): self
    {
        $this->value = SnakeCase::toSnakeCase($this->value);

        return $this;
    }

    public function screamingSnakeCase(): self
    {
        $this->value = ScreamingSnakeCase::toScreamingSnakeCase($this->value);

        return $this;
    }

    public function kebabCase(): self
    {
        $this->value = KebabCase::toKebabCase($this->value);

        return $this;
    }
}
