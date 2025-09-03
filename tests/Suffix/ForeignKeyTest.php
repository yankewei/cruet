<?php

declare(strict_types=1);

namespace Ykw\Cruet\Tests\Suffix;

use PHPUnit\Framework\TestCase;
use Ykw\Cruet\Inflector;

/**
 * Foreign key tests ported from Rust cruet
 * Based on tests/lib.rs in the Rust implementation
 */
class ForeignKeyTest extends TestCase
{
    // ========== BASIC FOREIGN KEY TESTS ==========
    
    public function testStrToForeignKey(): void
    {
        $this->assertSame('bar_id', Inflector::toForeignKey('Foo::Bar'));
    }
    
    public function testStrIsForeignKey(): void
    {
        $this->assertTrue(Inflector::isForeignKey('bar_id'));
    }
    
    public function testStrIsNotForeignKey(): void
    {
        $this->assertFalse(Inflector::isForeignKey('bar'));
    }

    // ========== COMPREHENSIVE FOREIGN KEY TESTS ==========
    
    /**
     * Data provider for foreign key generation tests
     */
    public static function foreignKeyProvider(): array
    {
        return [
            // Simple class names
            ['User', 'user_id'],
            ['Post', 'post_id'],
            ['Comment', 'comment_id'],
            ['Category', 'category_id'],
            
            // CamelCase class names
            ['UserAccount', 'user_account_id'],
            ['BlogPost', 'blog_post_id'],
            ['PostComment', 'post_comment_id'],
            ['UserProfile', 'user_profile_id'],
            
            // PascalCase class names
            ['UserRole', 'user_role_id'],
            ['AdminUser', 'admin_user_id'],
            ['SuperAdmin', 'super_admin_id'],
            
            // Namespaced class names (should extract just the class name)
            ['App::Models::User', 'user_id'],
            ['App::Models::UserAccount', 'user_account_id'],
            ['Foo::Bar', 'bar_id'],
            ['Foo::Bar::Baz', 'baz_id'],
            ['Very::Deep::Namespace::ClassName', 'class_name_id'],
            
            // Already snake_case
            ['user', 'user_id'],
            ['user_account', 'user_account_id'],
            ['blog_post', 'blog_post_id'],
            
            // Mixed cases
            ['XMLHttpRequest', 'xml_http_request_id'],
            ['HTMLElement', 'html_element_id'],
            ['JSONResponse', 'json_response_id'],
            
            // Single character
            ['A', 'a_id'],
            ['B', 'b_id'],
            
            // Numbers in class names
            ['User2', 'user_2_id'],
            ['API3Client', 'api_3_client_id'],
            ['Model1Test', 'model_1_test_id'],
        ];
    }
    
    /**
     * @dataProvider foreignKeyProvider
     */
    public function testForeignKeyGeneration(string $className, string $expectedForeignKey): void
    {
        $actualForeignKey = Inflector::toForeignKey($className);
        $this->assertSame($expectedForeignKey, $actualForeignKey, "Foreign key generation failed for: $className");
        
        // Test that the generated foreign key is detected as a foreign key
        $this->assertTrue(Inflector::isForeignKey($actualForeignKey), "Generated foreign key not detected: $actualForeignKey");
    }

    // ========== FOREIGN KEY DETECTION TESTS ==========
    
    /**
     * Data provider for foreign key detection tests
     */
    public static function foreignKeyDetectionProvider(): array
    {
        return [
            // Valid foreign keys (should return true)
            ['user_id', true],
            ['post_id', true],
            ['comment_id', true],
            ['user_account_id', true],
            ['blog_post_id', true],
            ['very_long_class_name_id', true],
            ['a_id', true],
            ['id', true], // Just 'id' should be considered a foreign key
            ['_id', true], // Edge case
            
            // Invalid foreign keys (should return false)
            ['user', false],
            ['post', false],
            ['user_account', false],
            ['blog_post', false],
            ['userid', false], // Not ending with _id
            ['user_idx', false], // Wrong suffix
            ['user_identity', false], // Contains 'id' but not ending with _id
            ['', false], // Empty string
            ['id_user', false], // id at the beginning
            ['user_id_extra', false], // id in middle
        ];
    }
    
    /**
     * @dataProvider foreignKeyDetectionProvider
     */
    public function testForeignKeyDetection(string $testString, bool $expectedResult): void
    {
        $actualResult = Inflector::isForeignKey($testString);
        $this->assertSame($expectedResult, $actualResult, "Foreign key detection failed for: '$testString'");
    }

    // ========== EDGE CASES ==========
    
    public function testEmptyStringForeignKey(): void
    {
        $this->assertSame('_id', Inflector::toForeignKey(''));
        $this->assertFalse(Inflector::isForeignKey(''));
    }
    
    public function testNamespaceWithoutClass(): void
    {
        // Test edge case where there's only namespace separators
        $this->assertSame('_id', Inflector::toForeignKey('::'));
        $this->assertSame('_id', Inflector::toForeignKey('Foo::'));
        $this->assertSame('_id', Inflector::toForeignKey('::Bar'));
    }
    
    public function testComplexNamespaces(): void
    {
        // Test very deep namespaces
        $deepNamespace = 'App::Modules::Admin::Controllers::Api::V1::UserManagement::UserController';
        $this->assertSame('user_controller_id', Inflector::toForeignKey($deepNamespace));
        $this->assertTrue(Inflector::isForeignKey('user_controller_id'));
    }
    
    public function testSpecialCharactersInClassName(): void
    {
        // Test class names with numbers and special patterns
        $this->assertSame('user_1_id', Inflector::toForeignKey('User1'));
        $this->assertSame('api_v_2_client_id', Inflector::toForeignKey('APIv2Client'));
        $this->assertSame('http_200_response_id', Inflector::toForeignKey('HTTP200Response'));
    }

    // ========== CONSISTENCY TESTS ==========
    
    public function testForeignKeyConsistency(): void
    {
        // Test that foreign key generation is consistent
        $className = 'UserAccount';
        $foreignKey1 = Inflector::toForeignKey($className);
        $foreignKey2 = Inflector::toForeignKey($className);
        
        $this->assertSame($foreignKey1, $foreignKey2, 'Foreign key generation should be consistent');
        $this->assertTrue(Inflector::isForeignKey($foreignKey1), 'Generated foreign key should be detected as such');
    }
    
    public function testRoundTripConsistency(): void
    {
        // Test various class names for consistency
        $classNames = [
            'User',
            'UserAccount', 
            'App::Models::Post',
            'Very::Deep::Namespace::SomeClass',
            'XMLHttpRequest',
            'simple_class',
        ];
        
        foreach ($classNames as $className) {
            $foreignKey = Inflector::toForeignKey($className);
            
            // Generated foreign key should always end with _id
            $this->assertStringEndsWith('_id', $foreignKey, "Foreign key should end with '_id': $foreignKey");
            
            // Generated foreign key should be detected as a foreign key
            $this->assertTrue(Inflector::isForeignKey($foreignKey), "Foreign key should be detected: $foreignKey");
            
            // Foreign key should be in snake_case format
            $this->assertSame($foreignKey, Inflector::toSnakeCase($foreignKey), "Foreign key should be in snake_case: $foreignKey");
        }
    }
    
    /**
     * Test integration with other inflections
     */
    public function testIntegrationWithOtherInflections(): void
    {
        // Test that foreign keys work well with other transformations
        $className = 'UserAccount';
        
        // Generate foreign key
        $foreignKey = Inflector::toForeignKey($className);
        $this->assertSame('user_account_id', $foreignKey);
        
        // Test with fluent interface
        $fluentResult = Inflector::convert($className)->foreignKey()->get();
        $this->assertSame($foreignKey, $fluentResult);
        
        // Test foreign key detection
        $this->assertTrue(Inflector::isForeignKey($foreignKey));
        
        // Test that foreign key is already in snake_case
        $this->assertSame($foreignKey, Inflector::toSnakeCase($foreignKey));
        
        // Test with various case transformations of the original
        $this->assertSame($foreignKey, Inflector::toForeignKey(Inflector::toSnakeCase($className)));
        $this->assertSame($foreignKey, Inflector::toForeignKey(Inflector::toCamelCase($className)));
        $this->assertSame($foreignKey, Inflector::toForeignKey(Inflector::toPascalCase($className)));
    }
}