# PHP Cruet

[![CI](https://github.com/yankewei/cruet-php/workflows/CI/badge.svg)](https://github.com/yankewei/cruet-php/actions)
[![PHP Version](https://img.shields.io/badge/php-%5E8.4-blue)](https://www.php.net/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![codecov](https://codecov.io/gh/yankewei/cruet-php/branch/main/graph/badge.svg)](https://codecov.io/gh/yankewei/cruet-php)

A fast, lightweight PHP library for string case conversions, based on the [Rust cruet library](https://github.com/chrislearn/cruet).

Provides clean, efficient case conversions with a simple API.

## Features

- **5 Case Conversions**: camelCase, PascalCase, snake_case, SCREAMING_SNAKE_CASE, kebab-case
- **Format Detection**: Identify string formats programmatically  
- **Fluent Interface**: Chain operations for complex transformations
- **High Performance**: Character-by-character processing with intelligent boundary detection
- **No Dependencies**: Pure PHP implementation, no external libraries required

## Installation

```bash
composer require ykw/cruet
```

Or add to your `composer.json`:

```json
{
    "require": {
        "ykw/cruet": "^1.0"
    }
}
```

## Quick Start

```php
use Ykw\Cruet\Inflector;

// Case conversions
echo Inflector::toCamelCase('user_account');         // userAccount
echo Inflector::toPascalCase('user_account');        // UserAccount  
echo Inflector::toSnakeCase('UserAccount');          // user_account
echo Inflector::toScreamingSnakeCase('UserAccount'); // USER_ACCOUNT
echo Inflector::toKebabCase('UserAccount');          // user-account

// Format detection
var_dump(Inflector::isCamelCase('userAccount'));     // true
var_dump(Inflector::isPascalCase('UserAccount'));    // true
var_dump(Inflector::isSnakeCase('user_account'));    // true

// Fluent interface
$result = Inflector::convert('user_account')
    ->pascalCase()      // UserAccount
    ->snakeCase()       // user_account
    ->screamingSnakeCase() // USER_ACCOUNT
    ->get();
echo $result; // USER_ACCOUNT
```

## Usage Examples

### Case Conversions

```php
use Ykw\Cruet\Inflector;

$input = 'user_profile_settings';

// All available case conversions
echo Inflector::toCamelCase($input);           // userProfileSettings
echo Inflector::toPascalCase($input);          // UserProfileSettings  
echo Inflector::toSnakeCase($input);           // user_profile_settings
echo Inflector::toScreamingSnakeCase($input);  // USER_PROFILE_SETTINGS
echo Inflector::toKebabCase($input);           // user-profile-settings
```

### Format Detection

```php
// Check string formats
var_dump(Inflector::isCamelCase('userAccount'));        // true
var_dump(Inflector::isPascalCase('UserAccount'));       // true
var_dump(Inflector::isSnakeCase('user_account'));       // true
var_dump(Inflector::isScreamingSnakeCase('USER_ACCOUNT')); // true
var_dump(Inflector::isKebabCase('user-account'));       // true
```

### Fluent Interface

Chain multiple operations together:

```php
use Ykw\Cruet\Inflector;

// Complex transformation chains
$result = Inflector::convert('user_account')
    ->pascalCase()       // UserAccount
    ->snakeCase()        // user_account  
    ->screamingSnakeCase() // USER_ACCOUNT
    ->get();

echo $result; // USER_ACCOUNT

// Another example
$result = Inflector::convert('AdminUser')
    ->snakeCase()        // admin_user
    ->kebabCase()        // admin-user
    ->get();
    
echo $result; // admin-user
```

## API Reference

### Static Case Conversion Methods
- `Inflector::toCamelCase(string $input): string`
- `Inflector::toPascalCase(string $input): string`  
- `Inflector::toSnakeCase(string $input): string`
- `Inflector::toScreamingSnakeCase(string $input): string`
- `Inflector::toKebabCase(string $input): string`

### Static Format Detection Methods
- `Inflector::isCamelCase(string $input): bool`
- `Inflector::isPascalCase(string $input): bool`
- `Inflector::isSnakeCase(string $input): bool`
- `Inflector::isScreamingSnakeCase(string $input): bool`
- `Inflector::isKebabCase(string $input): bool`

### Fluent Interface
- `Inflector::convert(string $input): Inflector` - Create fluent chain
- `->camelCase(): Inflector` - Convert to camelCase
- `->pascalCase(): Inflector` - Convert to PascalCase
- `->snakeCase(): Inflector` - Convert to snake_case
- `->screamingSnakeCase(): Inflector` - Convert to SCREAMING_SNAKE_CASE
- `->kebabCase(): Inflector` - Convert to kebab-case
- `->get(): string` - Get final result


## Requirements

- PHP 8.4 or higher
- No external dependencies

## Testing

Run the test suite:

```bash
composer test
```

Or run PHPUnit directly:

```bash
vendor/bin/phpunit
```

Generate test coverage:

```bash
composer run test-coverage
```

## Code Quality

This project uses [Mago](https://mago.carthage.software/) for code formatting, linting, and static analysis:

```bash
# Format code
mago format

# Run linter
mago lint

# Static analysis
mago analyze

# All quality checks
mago format && mago lint && mago analyze
```

Code quality checks run automatically via GitHub Actions on every push and pull request.

## Contributing

Contributions are welcome! Please ensure:

1. **Test Coverage**: Add tests for new functionality
2. **Documentation**: Update README for new features  
3. **Code Style**: Follow PSR-12 coding standards

## License

MIT License - see [LICENSE](LICENSE) file for details.

## Credits

Based on the [Rust cruet library](https://github.com/chrislearn/cruet) by Chris Learn.