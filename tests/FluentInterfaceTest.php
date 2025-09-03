<?php

declare(strict_types=1);

namespace Ykw\Cruet\Tests;

use PHPUnit\Framework\TestCase;
use Ykw\Cruet\Inflector;

/**
 * Fluent interface tests for method chaining
 * Tests the fluent API that allows chaining transformations
 */
class FluentInterfaceTest extends TestCase
{
    // ========== BASIC FLUENT INTERFACE TESTS ==========
    
    public function testFluentConvert(): void
    {
        $inflector = Inflector::convert('test_string');
        $this->assertInstanceOf(Inflector::class, $inflector);
        $this->assertSame('test_string', $inflector->get());
    }
    
    public function testSingleTransformation(): void
    {
        $result = Inflector::convert('user_account')->camelCase()->get();
        $this->assertSame('userAccount', $result);
    }

    // ========== CHAINING TESTS ==========
    
    public function testChainingCaseTransformations(): void
    {
        // Test various chaining combinations
        $result1 = Inflector::convert('user_accounts')
            ->pascalCase()
            ->get();
        $this->assertSame('UserAccounts', $result1);
        
        $result2 = Inflector::convert('UserAccount')
            ->snakeCase()
            ->get();
        $this->assertSame('user_account', $result2);
        
        $result3 = Inflector::convert('foo bar')
            ->camelCase()
            ->get();
        $this->assertSame('fooBar', $result3);
    }
    
    public function testChainingStringOperations(): void
    {
        // Test pluralization and singularization chaining
        $result1 = Inflector::convert('user')
            ->plural()
            ->get();
        $this->assertSame('users', $result1);
        
        $result2 = Inflector::convert('categories')
            ->singular()
            ->get();
        $this->assertSame('category', $result2);
        
        // Test namespace operations
        $result3 = Inflector::convert('App::Models::User')
            ->demodulized()
            ->get();
        $this->assertSame('User', $result3);
        
        $result4 = Inflector::convert('Foo::Bar::Baz')
            ->deconstantized()
            ->get();
        $this->assertSame('Foo::Bar', $result4);
    }
    
    public function testChainingNumberOperations(): void
    {
        // Test number operations
        $result1 = Inflector::convert('1')
            ->ordinalized()
            ->get();
        $this->assertSame('1st', $result1);
        
        $result2 = Inflector::convert('21st')
            ->deordinalized()
            ->get();
        $this->assertSame('21', $result2);
    }
    
    public function testChainingSuffixOperations(): void
    {
        // Test foreign key generation
        $result = Inflector::convert('UserAccount')
            ->foreignKey()
            ->get();
        $this->assertSame('user_account_id', $result);
    }

    // ========== COMPLEX CHAINING TESTS ==========
    
    public function testComplexChaining(): void
    {
        // Test the examples from the demo
        $result1 = Inflector::convert('user_accounts')
            ->pascalCase()
            ->singular()
            ->get();
        $this->assertSame('UserAccount', $result1);
        
        $result2 = Inflector::convert('MyModel')
            ->snakeCase()
            ->plural()
            ->get();
        $this->assertSame('my_models', $result2);
        
        $result3 = Inflector::convert('post_id')
            ->deordinalized()
            ->titleCase()
            ->get();
        $this->assertSame('Post Id', $result3);
    }
    
    public function testLongChains(): void
    {
        // Test longer chains of transformations
        $result1 = Inflector::convert('App::Models::UserAccount')
            ->demodulized()          // UserAccount
            ->snakeCase()            // user_account
            ->plural()               // user_accounts
            ->screamingSnakeCase()   // USER_ACCOUNTS
            ->get();
        $this->assertSame('USER_ACCOUNTS', $result1);
        
        $result2 = Inflector::convert('BlogPost')
            ->snakeCase()      // blog_post
            ->singular()       // blog_post (already singular)
            ->foreignKey()     // blog_post_id
            ->titleCase()      // Blog Post Id
            ->get();
        $this->assertSame('Blog Post Id', $result2);
    }

    // ========== METHOD AVAILABILITY TESTS ==========
    
    public function testAllFluentMethodsExist(): void
    {
        $inflector = Inflector::convert('test');
        
        // Test that all fluent methods exist and return Inflector instance
        $this->assertInstanceOf(Inflector::class, $inflector->camelCase());
        $this->assertInstanceOf(Inflector::class, $inflector->pascalCase());
        $this->assertInstanceOf(Inflector::class, $inflector->snakeCase());
        $this->assertInstanceOf(Inflector::class, $inflector->screamingSnakeCase());
        $this->assertInstanceOf(Inflector::class, $inflector->kebabCase());
        $this->assertInstanceOf(Inflector::class, $inflector->trainCase());
        $this->assertInstanceOf(Inflector::class, $inflector->sentenceCase());
        $this->assertInstanceOf(Inflector::class, $inflector->titleCase());
        $this->assertInstanceOf(Inflector::class, $inflector->classCase());
        $this->assertInstanceOf(Inflector::class, $inflector->tableCase());
        $this->assertInstanceOf(Inflector::class, $inflector->plural());
        $this->assertInstanceOf(Inflector::class, $inflector->singular());
        $this->assertInstanceOf(Inflector::class, $inflector->deconstantized());
        $this->assertInstanceOf(Inflector::class, $inflector->demodulized());
        $this->assertInstanceOf(Inflector::class, $inflector->ordinalized());
        $this->assertInstanceOf(Inflector::class, $inflector->deordinalized());
        $this->assertInstanceOf(Inflector::class, $inflector->foreignKey());
    }

    // ========== IMMUTABILITY TESTS ==========
    
    public function testFluentMethodsAreChainable(): void
    {
        // Test that each method in the chain works correctly
        $base = Inflector::convert('user_account');
        
        $camel = $base->camelCase();
        $this->assertSame('userAccount', $camel->get());
        
        $pascal = $base->pascalCase();
        $this->assertSame('UserAccount', $pascal->get());
        
        // Test that we can continue chaining from any point
        $result = $base->pascalCase()->snakeCase()->get();
        $this->assertSame('user_account', $result);
    }

    // ========== PRACTICAL USAGE TESTS ==========
    
    public function testPracticalUseCases(): void
    {
        // Model name to table name
        $tableName = Inflector::convert('UserAccount')
            ->tableCase()
            ->get();
        $this->assertSame('user_accounts', $tableName);
        
        // Table name to model name
        $modelName = Inflector::convert('user_accounts')
            ->classCase()
            ->get();
        $this->assertSame('UserAccount', $modelName);
        
        // Class name to foreign key
        $foreignKey = Inflector::convert('BlogPost')
            ->foreignKey()
            ->get();
        $this->assertSame('blog_post_id', $foreignKey);
        
        // Namespace to class name
        $className = Inflector::convert('App::Controllers::Api::UserController')
            ->demodulized()
            ->get();
        $this->assertSame('UserController', $className);
        
        // Database field to human readable
        $humanReadable = Inflector::convert('created_at_timestamp')
            ->titleCase()
            ->get();
        $this->assertSame('Created At Timestamp', $humanReadable);
    }

    // ========== COMPARISON WITH STATIC METHODS ==========
    
    public function testFluentVsStaticConsistency(): void
    {
        $testString = 'user_account';
        
        // Test that fluent and static methods produce same results
        $this->assertSame(
            Inflector::toCamelCase($testString),
            Inflector::convert($testString)->camelCase()->get()
        );
        
        $this->assertSame(
            Inflector::toPascalCase($testString),
            Inflector::convert($testString)->pascalCase()->get()
        );
        
        $this->assertSame(
            Inflector::toSnakeCase($testString),
            Inflector::convert($testString)->snakeCase()->get()
        );
        
        $this->assertSame(
            Inflector::toPlural($testString),
            Inflector::convert($testString)->plural()->get()
        );
        
        $this->assertSame(
            Inflector::toForeignKey($testString),
            Inflector::convert($testString)->foreignKey()->get()
        );
    }

    // ========== EDGE CASES ==========
    
    public function testEmptyStringChaining(): void
    {
        $result = Inflector::convert('')
            ->camelCase()
            ->pascalCase()
            ->snakeCase()
            ->get();
        $this->assertSame('', $result);
    }
    
    public function testSingleCharacterChaining(): void
    {
        $result = Inflector::convert('a')
            ->pascalCase()
            ->snakeCase()
            ->camelCase()
            ->get();
        $this->assertSame('a', $result);
    }
    
    public function testSpecialCharacterHandling(): void
    {
        $result = Inflector::convert('user@domain.com')
            ->camelCase()
            ->get();
        $this->assertSame('userDomainCom', $result);
    }

    // ========== PERFORMANCE CONSIDERATIONS ==========
    
    public function testChainingDoesNotCreateDeepNesting(): void
    {
        // Test that long chains don't cause issues
        $result = Inflector::convert('test_string')
            ->camelCase()
            ->pascalCase()
            ->snakeCase()
            ->camelCase()
            ->pascalCase()
            ->snakeCase()
            ->camelCase()
            ->pascalCase()
            ->snakeCase()
            ->get();
        
        $this->assertSame('test_string', $result);
    }
}