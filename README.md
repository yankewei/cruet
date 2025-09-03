# PHP Cruet

[![CI](https://github.com/yankewei/cruet-php/workflows/CI/badge.svg)](https://github.com/yankewei/cruet-php/actions)
[![PHP Version](https://img.shields.io/badge/php-%5E8.0-blue)](https://www.php.net/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![codecov](https://codecov.io/gh/yankewei/cruet-php/branch/main/graph/badge.svg)](https://codecov.io/gh/yankewei/cruet-php)

A comprehensive PHP library for string inflections, direct port of the [Rust cruet library](https://github.com/chrislearn/cruet).

PHP Cruet provides case conversions, pluralization, ordinalization, and other string transformations with **100% algorithmic compatibility** with the original Rust implementation.

## Features

- **10 Case Conversions**: camelCase, PascalCase, snake_case, SCREAMING_SNAKE_CASE, kebab-case, Train-Case, Sentence case, Title Case, ClassCase, table_case
- **Pluralization & Singularization**: Complete English language rules with 202 uncountable words and extensive regex patterns
- **Number Ordinalization**: Convert numbers to ordinal form (1st, 2nd, 3rd, etc.)
- **Namespace Operations**: Extract and manipulate namespaced strings
- **Foreign Key Generation**: Convert class names to database foreign key format
- **Fluent Interface**: Chain operations for complex transformations
- **Format Detection**: Identify string formats programmatically

## Installation

```bash
composer require cruet/php-cruet
```

Or download and include manually:

```php
require_once 'path/to/cruet/src/Inflector.php';
// ... include other files as needed
```

## Quick Start

```php
use Ykw\Cruet\Inflector;

// Case conversions
echo Inflector::toCamelCase('user_account');        // userAccount
echo Inflector::toPascalCase('user_account');       // UserAccount  
echo Inflector::toSnakeCase('UserAccount');         // user_account
echo Inflector::toKebabCase('UserAccount');         // user-account
echo Inflector::toTitleCase('user_account');        // User Account

// Pluralization
echo Inflector::toPlural('user');                   // users
echo Inflector::toPlural('child');                  // children
echo Inflector::toSingular('children');             // child

// Ordinalization
echo Inflector::ordinalize('1');                    // 1st
echo Inflector::ordinalize('22');                   // 22nd

// Special cases
echo Inflector::toClassCase('user_accounts');       // UserAccount (singularized)
echo Inflector::toTableCase('User');                // users (pluralized)
echo Inflector::toForeignKey('UserAccount');        // user_account_id
```

## Comprehensive Usage Examples

### Case Conversions

```php
use Ykw\Cruet\Inflector;

$input = 'user_profile_settings';

// All case conversions
echo Inflector::toCamelCase($input);           // userProfileSettings
echo Inflector::toPascalCase($input);          // UserProfileSettings
echo Inflector::toSnakeCase($input);           // user_profile_settings
echo Inflector::toScreamingSnakeCase($input);  // USER_PROFILE_SETTINGS
echo Inflector::toKebabCase($input);           // user-profile-settings
echo Inflector::toTrainCase($input);           // User-Profile-Settings
echo Inflector::toSentenceCase($input);        // User profile settings
echo Inflector::toTitleCase($input);           // User Profile Settings
echo Inflector::toClassCase($input);           // UserProfileSetting (singularized)
echo Inflector::toTableCase($input);           // user_profile_settings (pluralized)
```

### Format Detection

```php
// Check string formats
var_dump(Inflector::isCamelCase('userAccount'));        // true
var_dump(Inflector::isPascalCase('UserAccount'));       // true
var_dump(Inflector::isSnakeCase('user_account'));       // true
var_dump(Inflector::isKebabCase('user-account'));       // true
var_dump(Inflector::isForeignKey('user_account_id'));   // true
```

### Pluralization & Singularization

```php
// Regular plurals
echo Inflector::toPlural('book');              // books
echo Inflector::toPlural('class');             // classes
echo Inflector::toPlural('city');              // cities

// Irregular plurals  
echo Inflector::toPlural('child');             // children
echo Inflector::toPlural('person');            // people
echo Inflector::toPlural('ox');                // oxen
echo Inflector::toPlural('mouse');             // mice

// Uncountable words (remain unchanged)
echo Inflector::toPlural('sheep');             // sheep
echo Inflector::toPlural('equipment');         // equipment

// Singularization
echo Inflector::toSingular('books');           // book
echo Inflector::toSingular('children');        // child
echo Inflector::toSingular('mice');            // mouse
```

### Number Ordinalization

```php
// Basic ordinalization
echo Inflector::ordinalize('1');               // 1st
echo Inflector::ordinalize('2');               // 2nd
echo Inflector::ordinalize('3');               // 3rd
echo Inflector::ordinalize('4');               // 4th

// Special cases (teens)
echo Inflector::ordinalize('11');              // 11th
echo Inflector::ordinalize('12');              // 12th
echo Inflector::ordinalize('13');              // 13th

// Complex numbers
echo Inflector::ordinalize('21');              // 21st
echo Inflector::ordinalize('22');              // 22nd
echo Inflector::ordinalize('101');             // 101st

// Deordinalization
echo Inflector::deordinalize('1st');           // 1
echo Inflector::deordinalize('22nd');          // 22
```

### Namespace Operations

```php
// Demodulize (extract class name)
echo Inflector::demodulize('App\\Models\\User');      // User
echo Inflector::demodulize('Foo::Bar::Baz');          // Baz

// Deconstantize (extract parent namespace)
echo Inflector::deconstantize('App\\Models\\User');   // Model  
echo Inflector::deconstantize('Foo::Bar::Baz');       // Bar
```

### Fluent Interface

Chain multiple operations together:

```php
use Ykw\Cruet\Inflector;

// Complex transformation chains
$result = Inflector::convert('user_accounts')
    ->toPascalCase()     // UserAccounts
    ->toSingular()       // UserAccount
    ->toSnakeCase()      // user_account  
    ->toForeignKey()     // user_account_id
    ->get();

echo $result; // user_account_id

// Another example
$result = Inflector::convert('AdminUser')
    ->toSnakeCase()      // admin_user
    ->toPlural()         // admin_users
    ->toTitleCase()      // Admin Users
    ->get();
    
echo $result; // Admin Users
```

### Trait Usage

Extend your own string classes:

```php
use Ykw\Cruet\Traits\StringInflection;

class MyString 
{
    use StringInflection;
    
    private string $value;
    
    public function __construct(string $value) 
    {
        $this->value = $value;
    }
    
    public function getValue(): string 
    {
        return $this->value;
    }
}

$str = new MyString('user_account');
echo $str->toCamelCase();        // userAccount
echo $str->toTitleCase();        // User Account
```

## Complete API Reference

### Case Conversions
- `toCamelCase(string $input): string`
- `toPascalCase(string $input): string`  
- `toSnakeCase(string $input): string`
- `toScreamingSnakeCase(string $input): string`
- `toKebabCase(string $input): string`
- `toTrainCase(string $input): string`
- `toSentenceCase(string $input): string`
- `toTitleCase(string $input): string`
- `toClassCase(string $input): string`
- `toTableCase(string $input): string`

### Format Detection
- `isCamelCase(string $input): bool`
- `isPascalCase(string $input): bool`
- `isSnakeCase(string $input): bool`
- `isScreamingSnakeCase(string $input): bool`
- `isKebabCase(string $input): bool`
- `isTrainCase(string $input): bool`
- `isSentenceCase(string $input): bool`
- `isTitleCase(string $input): bool`
- `isClassCase(string $input): bool`
- `isTableCase(string $input): bool`

### String Operations
- `toPlural(string $input): string`
- `toSingular(string $input): string`
- `deconstantize(string $input): string`
- `demodulize(string $input): string`

### Number Operations
- `ordinalize(string $input): string`
- `deordinalize(string $input): string`

### Suffix Operations
- `toForeignKey(string $input): string`
- `isForeignKey(string $input): bool`

### Fluent Interface
- `convert(string $input): InflectorChain`

## Implementation Principles

### Core Architecture

PHP Cruet follows a **modular, algorithm-focused design** that directly mirrors the Rust implementation:

#### 1. **Central Conversion Engine (`CaseConverter`)**
- **`toCaseSnakeLike()`**: Handles all snake-style cases (snake_case, SCREAMING_SNAKE_CASE, kebab-case, etc.)
- **`toCaseCamelLike()`**: Handles all camel-style cases (camelCase, PascalCase, Train-Case, etc.)
- **Character-by-character processing**: Analyzes each character with context awareness
- **Separator detection**: Intelligent boundary detection between words

#### 2. **Smart Boundary Detection**
```php
// Detects boundaries between:
'fooBar3'     → 'foo_bar_3_id'    // letter-digit boundaries
'XMLParser'   → 'xml_parser'      // uppercase-lowercase boundaries  
'HTMLToXML'   → 'html_to_xml'     // consecutive uppercase handling
```

#### 3. **Linguistic Rule Engine**
- **29 Pluralization Rules**: Covers regular (-s, -es) and irregular patterns (child→children, ox→oxen)
- **41 Singularization Rules**: Reverse transformations with special case handling
- **202 Uncountable Words**: Words that never change form (sheep, equipment, advice)
- **Context-sensitive**: Rules applied in priority order to handle conflicts

#### 4. **Unified Interface Pattern**
- **Static Methods**: `Inflector::toCamelCase()` for direct usage
- **Fluent Interface**: `Inflector::convert()->toCamelCase()->toPlural()` for chaining
- **Trait System**: `StringInflection` trait for extending custom classes

### Key Algorithms

#### Snake-Like Conversion Process
1. **Character Analysis**: Each character examined with surrounding context
2. **Boundary Detection**: Identifies word boundaries using:
   - Case transitions (lower→upper)
   - Letter-digit boundaries  
   - Existing separators (_, -, space)
3. **Separator Injection**: Inserts appropriate separators at boundaries
4. **Case Transformation**: Applies target case (lower/upper) to each segment

#### Pluralization Logic
1. **Uncountable Check**: Returns unchanged if word is uncountable
2. **Irregular Rules**: Applies special cases (person→people, mouse→mice)
3. **Pattern Matching**: Uses regex patterns for regular transformations:
   - Words ending in 's', 'ch', 'sh' → add 'es'
   - Words ending in consonant+'y' → change to 'ies'
   - Default → add 's'

#### Foreign Key Generation
```php
'UserAccount'     → 'user_account_id'     // PascalCase → snake_case + _id
'App::User::Role' → 'role_id'             // Namespaced → extract last + _id  
'user_account_id' → 'user_account_id'     // Already foreign key → unchanged
```

### Algorithm Compatibility

This library maintains **100% algorithmic compatibility** with Rust cruet:

- **Identical Core Algorithms**: Both `toCaseSnakeLike()` and `toCaseCamelLike()` algorithms are exactly ported
- **Same Rule Sets**: All 29 pluralization rules and 41 singularization rules are identical  
- **Same Word Lists**: All 202 uncountable words match exactly
- **Same Edge Cases**: All special cases and boundary conditions behave identically
- **Same Test Cases**: Passes the same test scenarios as the Rust version

### Performance Characteristics

- **String Processing**: O(n) time complexity for most operations
- **Memory Efficient**: No intermediate string arrays, character-by-character processing
- **Rule Caching**: Static rule sets loaded once, reused across calls
- **No Dependencies**: Pure PHP implementation, no external libraries required

## Requirements

- PHP 8.0 or higher
- No external dependencies

## Testing

Run the demo to see all functionality:

```bash
php demo.php
```

Run individual test files:

```bash
php test_basic.php          # Basic case conversions
php test_pluralize.php      # Pluralization tests  
php test_numbers.php        # Number operations
php test_all_new.php        # All advanced features
```

## Contributing

Contributions are welcome! Please ensure:

1. **Algorithmic Consistency**: Any changes must maintain compatibility with Rust cruet
2. **Test Coverage**: Add tests for new functionality
3. **Documentation**: Update README for new features
4. **Code Style**: Follow PSR-12 coding standards

## License

MIT License - see [LICENSE](LICENSE) file for details.

## Credits

- Original Rust implementation: [cruet](https://github.com/chrislearn/cruet) by Chris Learn
- PHP port: Community contributors

## Related Projects

- [Rust cruet](https://github.com/chrislearn/cruet) - Original Rust implementation
- [Laravel Str](https://laravel.com/docs/strings) - Laravel's string utilities  
- [Doctrine Inflector](https://github.com/doctrine/inflector) - Doctrine's inflection library