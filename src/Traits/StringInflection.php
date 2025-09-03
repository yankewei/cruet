<?php

declare(strict_types=1);

namespace Ykw\Cruet\Traits;

use Ykw\Cruet\Inflector;

/**
 * Trait to add inflection methods to string-like objects
 * Can be used to extend custom string classes
 */
trait StringInflection
{
    abstract public function getValue(): string;
    
    // ========== CASE TRANSFORMATIONS ==========
    
    public function toCamelCase(): string
    {
        return Inflector::toCamelCase($this->getValue());
    }

    public function isCamelCase(): bool
    {
        return Inflector::isCamelCase($this->getValue());
    }

    public function toPascalCase(): string
    {
        return Inflector::toPascalCase($this->getValue());
    }

    public function isPascalCase(): bool
    {
        return Inflector::isPascalCase($this->getValue());
    }

    public function toSnakeCase(): string
    {
        return Inflector::toSnakeCase($this->getValue());
    }

    public function isSnakeCase(): bool
    {
        return Inflector::isSnakeCase($this->getValue());
    }

    public function toScreamingSnakeCase(): string
    {
        return Inflector::toScreamingSnakeCase($this->getValue());
    }

    public function isScreamingSnakeCase(): bool
    {
        return Inflector::isScreamingSnakeCase($this->getValue());
    }

    public function toKebabCase(): string
    {
        return Inflector::toKebabCase($this->getValue());
    }

    public function isKebabCase(): bool
    {
        return Inflector::isKebabCase($this->getValue());
    }

    public function toTrainCase(): string
    {
        return Inflector::toTrainCase($this->getValue());
    }

    public function isTrainCase(): bool
    {
        return Inflector::isTrainCase($this->getValue());
    }

    public function toSentenceCase(): string
    {
        return Inflector::toSentenceCase($this->getValue());
    }

    public function isSentenceCase(): bool
    {
        return Inflector::isSentenceCase($this->getValue());
    }

    public function toTitleCase(): string
    {
        return Inflector::toTitleCase($this->getValue());
    }

    public function isTitleCase(): bool
    {
        return Inflector::isTitleCase($this->getValue());
    }

    public function toClassCase(): string
    {
        return Inflector::toClassCase($this->getValue());
    }

    public function isClassCase(): bool
    {
        return Inflector::isClassCase($this->getValue());
    }

    public function toTableCase(): string
    {
        return Inflector::toTableCase($this->getValue());
    }

    public function isTableCase(): bool
    {
        return Inflector::isTableCase($this->getValue());
    }

    // ========== STRING OPERATIONS ==========

    public function toPlural(): string
    {
        return Inflector::toPlural($this->getValue());
    }

    public function toSingular(): string
    {
        return Inflector::toSingular($this->getValue());
    }

    public function deconstantize(): string
    {
        return Inflector::deconstantize($this->getValue());
    }

    public function demodulize(): string
    {
        return Inflector::demodulize($this->getValue());
    }

    // ========== NUMBER OPERATIONS ==========

    public function ordinalize(): string
    {
        return Inflector::ordinalize($this->getValue());
    }

    public function deordinalize(): string
    {
        return Inflector::deordinalize($this->getValue());
    }

    // ========== SUFFIX OPERATIONS ==========

    public function toForeignKey(): string
    {
        return Inflector::toForeignKey($this->getValue());
    }

    public function isForeignKey(): bool
    {
        return Inflector::isForeignKey($this->getValue());
    }
}