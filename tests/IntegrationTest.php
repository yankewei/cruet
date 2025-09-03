<?php

declare(strict_types=1);

namespace Ykw\Cruet\Tests;

use PHPUnit\Framework\TestCase;
use Ykw\Cruet\Inflector;

/**
 * Integration tests that verify the entire system works together
 * These tests simulate real-world usage scenarios
 */
class IntegrationTest extends TestCase
{
    // ========== REAL-WORLD SCENARIOS ==========
    
    public function testDatabaseModelScenario(): void
    {
        // Scenario: Converting between model names and database table names
        $modelName = 'UserAccount';
        
        // Model to table
        $tableName = Inflector::toTableCase($modelName);
        $this->assertSame('user_accounts', $tableName);
        
        // Table back to model
        $backToModel = Inflector::toClassCase($tableName);
        $this->assertSame('UserAccount', $backToModel);
        
        // Generate foreign key
        $foreignKey = Inflector::toForeignKey($modelName);
        $this->assertSame('user_account_id', $foreignKey);
        $this->assertTrue(Inflector::isForeignKey($foreignKey));
    }
    
    public function testApiEndpointScenario(): void
    {
        // Scenario: Converting between different API naming conventions
        $resourceName = 'user_profiles';
        
        // Database table to camelCase for JSON API
        $jsonKey = Inflector::toCamelCase(Inflector::toSingular($resourceName));
        $this->assertSame('userProfile', $jsonKey);
        
        // To URL slug format
        $urlSlug = Inflector::toKebabCase(Inflector::toSingular($resourceName));
        $this->assertSame('user-profile', $urlSlug);
        
        // To class name
        $className = Inflector::toClassCase($resourceName);
        $this->assertSame('UserProfile', $className);
    }
    
    public function testNamespaceScenario(): void
    {
        // Scenario: Working with namespaced classes
        $fullyQualifiedClass = 'App::Modules::Admin::Controllers::UserManagementController';
        
        // Extract class name
        $className = Inflector::demodulize($fullyQualifiedClass);
        $this->assertSame('UserManagementController', $className);
        
        // Extract namespace
        $namespace = Inflector::deconstantize($fullyQualifiedClass);
        $this->assertSame('App::Modules::Admin::Controllers', $namespace);
        
        // Generate table name from class
        $tableName = Inflector::toTableCase($className);
        $this->assertSame('user_management_controllers', $tableName);
        
        // Generate foreign key
        $foreignKey = Inflector::toForeignKey($className);
        $this->assertSame('user_management_controller_id', $foreignKey);
    }

    // ========== CROSS-FEATURE COMPATIBILITY ==========
    
    public function testAllTransformationsAreReversible(): void
    {
        $originalWords = [
            'user_account',
            'BlogPost', 
            'admin_users',
            'XMLHttpRequest',
            'simple_word'
        ];
        
        foreach ($originalWords as $word) {
            // Test that snake_case transformations are reversible through camelCase
            $snake = Inflector::toSnakeCase($word);
            $backToCamel = Inflector::toCamelCase($snake);
            $backToSnake = Inflector::toSnakeCase($backToCamel);
            $this->assertSame($snake, $backToSnake, "Snake case transformation not reversible for: $word");
            
            // Test pluralization reversibility
            $plural = Inflector::toPlural(Inflector::toSingular($word));
            $singular = Inflector::toSingular($plural);
            // Note: This might not always be exact due to irregular plurals, but should be consistent
            $backToPlural = Inflector::toPlural($singular);
            $this->assertSame($plural, $backToPlural, "Pluralization not consistent for: $word");
        }
    }
    
    public function testOrdinalizationRoundTrips(): void
    {
        $numbers = ['1', '2', '3', '11', '12', '13', '21', '22', '23', '101', '111', '121'];
        
        foreach ($numbers as $number) {
            $ordinal = Inflector::ordinalize($number);
            $backToNumber = Inflector::deordinalize($ordinal);
            $this->assertSame($number, $backToNumber, "Ordinalization round-trip failed for: $number");
        }
    }

    // ========== FORMAT DETECTION INTEGRATION ==========
    
    public function testFormatDetectionAccuracy(): void
    {
        // Test various strings and their expected format detections
        $testCases = [
            ['fooBar', 'camelCase'],
            ['FooBar', 'PascalCase'],
            ['foo_bar', 'snake_case'],
            ['FOO_BAR', 'SCREAMING_SNAKE_CASE'],
            ['foo-bar', 'kebab-case'],
            ['Foo-Bar', 'Train-Case'],
            ['Foo bar', 'Sentence case'],
            ['Foo Bar', 'Title Case'],
            ['foo_bars', 'table_case'],
            ['user_id', 'foreign_key'],
        ];
        
        foreach ($testCases as [$testString, $expectedFormat]) {
            $detected = match($expectedFormat) {
                'camelCase' => Inflector::isCamelCase($testString),
                'PascalCase' => Inflector::isPascalCase($testString),
                'snake_case' => Inflector::isSnakeCase($testString),
                'SCREAMING_SNAKE_CASE' => Inflector::isScreamingSnakeCase($testString),
                'kebab-case' => Inflector::isKebabCase($testString),
                'Train-Case' => Inflector::isTrainCase($testString),
                'Sentence case' => Inflector::isSentenceCase($testString),
                'Title Case' => Inflector::isTitleCase($testString),
                'table_case' => Inflector::isTableCase($testString),
                'foreign_key' => Inflector::isForeignKey($testString),
                default => false
            };
            
            $this->assertTrue($detected, "Format detection failed: '$testString' should be detected as '$expectedFormat'");
        }
    }

    // ========== FLUENT INTERFACE INTEGRATION ==========
    
    public function testComplexFluentChains(): void
    {
        // Test complex real-world fluent chains
        
        // Convert a namespaced class to a database foreign key
        $result1 = Inflector::convert('App::Models::UserAccount')
            ->demodulized()    // UserAccount
            ->foreignKey()     // user_account_id
            ->get();
        $this->assertSame('user_account_id', $result1);
        
        // Convert database table to API resource name
        $result2 = Inflector::convert('blog_post_comments')
            ->singular()       // blog_post_comment
            ->camelCase()      // blogPostComment
            ->get();
        $this->assertSame('blogPostComment', $result2);
        
        // Convert camelCase to human readable
        $result3 = Inflector::convert('userAccountSettings')
            ->titleCase()      // User Account Settings
            ->get();
        $this->assertSame('User Account Settings', $result3);
        
        // Create a table name from a complex class name
        $result4 = Inflector::convert('XMLHttpRequestHandler')
            ->tableCase()      // xml_http_request_handlers
            ->get();
        $this->assertSame('xml_http_request_handlers', $result4);
    }

    // ========== PERFORMANCE AND STRESS TESTS ==========
    
    public function testLargeStringHandling(): void
    {
        // Test with longer strings
        $longString = 'VeryLongClassNameWithManyWordsAndComplexStructureThatShouldBeHandledCorrectly';
        
        $snakeCase = Inflector::toSnakeCase($longString);
        $this->assertStringContainsString('very_long_class_name', $snakeCase);
        
        $camelCase = Inflector::toCamelCase($snakeCase);
        $this->assertStringStartsWith('very', $camelCase);
        
        $foreignKey = Inflector::toForeignKey($longString);
        $this->assertStringEndsWith('_id', $foreignKey);
    }
    
    public function testManyTransformations(): void
    {
        // Test applying many transformations in sequence
        $original = 'user_account';
        $current = $original;
        
        // Apply 100 transformations in a cycle
        for ($i = 0; $i < 100; $i++) {
            $current = match($i % 4) {
                0 => Inflector::toCamelCase($current),
                1 => Inflector::toSnakeCase($current),
                2 => Inflector::toPascalCase($current),
                3 => Inflector::toSnakeCase($current),
            };
        }
        
        // Should end back at snake_case version of original
        $this->assertSame('user_account', $current);
    }

    // ========== COMPATIBILITY WITH RUST IMPLEMENTATION ==========
    
    public function testRustCompatibilityExamples(): void
    {
        // Test examples that exactly match the Rust test cases
        
        // From Rust str_tests macro
        $this->assertSame('fooBar', Inflector::toCamelCase('foo_bar'));
        $this->assertSame('fooBar1', Inflector::toCamelCase('foo_bar_1'));
        $this->assertTrue(Inflector::isCamelCase('fooBar'));
        $this->assertFalse(Inflector::isCamelCase('foo_bar'));
        
        $this->assertSame('FOO_BAR', Inflector::toScreamingSnakeCase('fooBar'));
        $this->assertTrue(Inflector::isScreamingSnakeCase('FOO_BAR'));
        $this->assertFalse(Inflector::isScreamingSnakeCase('foo_bar'));
        
        $this->assertSame('foo_bar', Inflector::toSnakeCase('fooBar'));
        $this->assertTrue(Inflector::isSnakeCase('foo_bar'));
        $this->assertFalse(Inflector::isSnakeCase('fooBar'));
        
        $this->assertSame('foo-bar', Inflector::toKebabCase('fooBar'));
        $this->assertTrue(Inflector::isKebabCase('foo-bar'));
        $this->assertFalse(Inflector::isKebabCase('fooBar'));
        
        $this->assertSame('Foo-Bar', Inflector::toTrainCase('fooBar'));
        $this->assertTrue(Inflector::isTrainCase('Foo-Bar'));
        $this->assertFalse(Inflector::isTrainCase('FOO-Bar'));
        
        $this->assertSame('Foo bar', Inflector::toSentenceCase('fooBar'));
        $this->assertTrue(Inflector::isSentenceCase('Foo bar'));
        $this->assertFalse(Inflector::isSentenceCase('foo_bar'));
        
        $this->assertSame('Foo Bar', Inflector::toTitleCase('fooBar'));
        $this->assertTrue(Inflector::isTitleCase('Foo Bar'));
        $this->assertFalse(Inflector::isTitleCase('Foo_Bar'));
        
        $this->assertSame('1st', Inflector::ordinalize('1'));
        $this->assertSame('1', Inflector::deordinalize('1st'));
        
        $this->assertSame('bar_id', Inflector::toForeignKey('Foo::Bar'));
        $this->assertTrue(Inflector::isForeignKey('bar_id'));
        $this->assertFalse(Inflector::isForeignKey('bar'));
        
        // From Rust gated_str_tests macro
        $this->assertSame('Foo', Inflector::toClassCase('foo'));
        $this->assertTrue(Inflector::isClassCase('Foo'));
        $this->assertFalse(Inflector::isClassCase('foo'));
        
        $this->assertSame('foo_bars', Inflector::toTableCase('fooBar'));
        $this->assertTrue(Inflector::isTableCase('foo_bars'));
        $this->assertFalse(Inflector::isTableCase('fooBars'));
        
        $this->assertSame('crates', Inflector::toPlural('crate'));
        $this->assertSame('crate', Inflector::toSingular('crates'));
        
        $this->assertSame('Bar', Inflector::demodulize('Foo::Bar'));
        $this->assertSame('Foo', Inflector::deconstantize('Foo::Bar'));
    }

    // ========== ERROR HANDLING AND EDGE CASES ==========
    
    public function testUnusualInputHandling(): void
    {
        // Test with various unusual inputs
        $unusualInputs = [
            '',                    // Empty string
            ' ',                   // Single space
            '123',                 // Pure numbers
            '___',                 // Only separators
            'a',                   // Single character
            'A1B2C3',              // Mixed letters and numbers
            'user@domain.com',     // Email-like
            'snake_case_',         // Trailing separator
            '_leading_underscore', // Leading separator
            'ALLCAPS',            // All capitals
            'lowercase',          // All lowercase
        ];
        
        foreach ($unusualInputs as $input) {
            // Should not throw exceptions
            try {
                Inflector::toCamelCase($input);
                Inflector::toSnakeCase($input);
                Inflector::toPlural($input);
                Inflector::toSingular($input);
                Inflector::ordinalize($input);
                Inflector::deordinalize($input);
                Inflector::toForeignKey($input);
                $this->assertTrue(true, "No exception thrown for input: '$input'");
            } catch (\Throwable $e) {
                $this->fail("Exception thrown for input '$input': " . $e->getMessage());
            }
        }
    }
}