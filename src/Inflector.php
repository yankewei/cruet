<?php

declare(strict_types=1);

namespace Ykw\Cruet;

use Ykw\Cruet\Case\CamelCase;
use Ykw\Cruet\Case\PascalCase;
use Ykw\Cruet\Case\SnakeCase;
use Ykw\Cruet\Case\ScreamingSnakeCase;
use Ykw\Cruet\Case\KebabCase;
use Ykw\Cruet\Case\TrainCase;
use Ykw\Cruet\Case\SentenceCase;
use Ykw\Cruet\Case\TitleCase;
use Ykw\Cruet\Case\ClassCase;
use Ykw\Cruet\Case\TableCase;
use Ykw\Cruet\StringOp\Pluralize;
use Ykw\Cruet\StringOp\Singularize;
use Ykw\Cruet\StringOp\Deconstantize;
use Ykw\Cruet\StringOp\Demodulize;
use Ykw\Cruet\Number\Ordinalize;
use Ykw\Cruet\Number\Deordinalize;
use Ykw\Cruet\Suffix\ForeignKey;

/**
 * Main Inflector class - unified entry point for all string transformations
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

    // ========== STATIC CASE TRANSFORMATIONS ==========

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

    public static function toTrainCase(string $string): string
    {
        return TrainCase::toTrainCase($string);
    }

    public static function isTrainCase(string $string): bool
    {
        return TrainCase::isTrainCase($string);
    }

    public static function toSentenceCase(string $string): string
    {
        return SentenceCase::toSentenceCase($string);
    }

    public static function isSentenceCase(string $string): bool
    {
        return SentenceCase::isSentenceCase($string);
    }

    public static function toTitleCase(string $string): string
    {
        return TitleCase::toTitleCase($string);
    }

    public static function isTitleCase(string $string): bool
    {
        return TitleCase::isTitleCase($string);
    }

    public static function toClassCase(string $string): string
    {
        return ClassCase::toClassCase($string);
    }

    public static function isClassCase(string $string): bool
    {
        return ClassCase::isClassCase($string);
    }

    public static function toTableCase(string $string): string
    {
        return TableCase::toTableCase($string);
    }

    public static function isTableCase(string $string): bool
    {
        return TableCase::isTableCase($string);
    }

    // ========== STATIC STRING OPERATIONS ==========

    public static function toPlural(string $string): string
    {
        return Pluralize::toPlural($string);
    }

    public static function toSingular(string $string): string
    {
        return Singularize::toSingular($string);
    }

    public static function deconstantize(string $string): string
    {
        return Deconstantize::deconstantize($string);
    }

    public static function demodulize(string $string): string
    {
        return Demodulize::demodulize($string);
    }

    // ========== STATIC NUMBER OPERATIONS ==========

    public static function ordinalize(string $string): string
    {
        return Ordinalize::ordinalize($string);
    }

    public static function deordinalize(string $string): string
    {
        return Deordinalize::deordinalize($string);
    }

    // ========== STATIC SUFFIX OPERATIONS ==========

    public static function toForeignKey(string $string): string
    {
        return ForeignKey::toForeignKey($string);
    }

    public static function isForeignKey(string $string): bool
    {
        return ForeignKey::isForeignKey($string);
    }

    // ========== FLUENT INTERFACE METHODS ==========
    // Note: Using different method names to avoid conflicts with static methods

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

    public function trainCase(): self
    {
        $this->value = TrainCase::toTrainCase($this->value);
        return $this;
    }

    public function sentenceCase(): self
    {
        $this->value = SentenceCase::toSentenceCase($this->value);
        return $this;
    }

    public function titleCase(): self
    {
        $this->value = TitleCase::toTitleCase($this->value);
        return $this;
    }

    public function classCase(): self
    {
        $this->value = ClassCase::toClassCase($this->value);
        return $this;
    }

    public function tableCase(): self
    {
        $this->value = TableCase::toTableCase($this->value);
        return $this;
    }

    public function plural(): self
    {
        $this->value = Pluralize::toPlural($this->value);
        return $this;
    }

    public function singular(): self
    {
        $this->value = Singularize::toSingular($this->value);
        return $this;
    }

    public function deconstantized(): self
    {
        $this->value = Deconstantize::deconstantize($this->value);
        return $this;
    }

    public function demodulized(): self
    {
        $this->value = Demodulize::demodulize($this->value);
        return $this;
    }

    public function ordinalized(): self
    {
        $this->value = Ordinalize::ordinalize($this->value);
        return $this;
    }

    public function deordinalized(): self
    {
        $this->value = Deordinalize::deordinalize($this->value);
        return $this;
    }

    public function foreignKey(): self
    {
        $this->value = ForeignKey::toForeignKey($this->value);
        return $this;
    }
}