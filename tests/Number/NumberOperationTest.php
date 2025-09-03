<?php

declare(strict_types=1);

namespace Ykw\Cruet\Tests\Number;

use PHPUnit\Framework\TestCase;
use Ykw\Cruet\Inflector;

/**
 * Number operation tests ported from Rust cruet
 * Based on tests/lib.rs in the Rust implementation
 */
class NumberOperationTest extends TestCase
{
    // ========== BASIC ORDINALIZATION TESTS ==========
    
    public function testStrOrdinalize(): void
    {
        $this->assertSame('1st', Inflector::ordinalize('1'));
    }
    
    public function testStrDeordinalize(): void
    {
        $this->assertSame('1', Inflector::deordinalize('1st'));
    }

    // ========== COMPREHENSIVE ORDINALIZATION TESTS ==========
    
    /**
     * Data provider for ordinalization tests from Rust implementation
     */
    public static function ordinalizationProvider(): array
    {
        return [
            // Basic numbers 1-10
            ['1', '1st'],
            ['2', '2nd'], 
            ['3', '3rd'],
            ['4', '4th'],
            ['5', '5th'],
            ['6', '6th'],
            ['7', '7th'],
            ['8', '8th'],
            ['9', '9th'],
            ['10', '10th'],
            
            // Teen numbers (special cases)
            ['11', '11th'],
            ['12', '12th'],
            ['13', '13th'],
            ['14', '14th'],
            ['15', '15th'],
            ['16', '16th'],
            ['17', '17th'],
            ['18', '18th'],
            ['19', '19th'],
            
            // Twenty series
            ['20', '20th'],
            ['21', '21st'],
            ['22', '22nd'],
            ['23', '23rd'],
            ['24', '24th'],
            ['25', '25th'],
            
            // Thirty series
            ['30', '30th'],
            ['31', '31st'],
            ['32', '32nd'],
            ['33', '33rd'],
            ['34', '34th'],
            
            // Larger numbers
            ['100', '100th'],
            ['101', '101st'],
            ['102', '102nd'],
            ['103', '103rd'],
            ['104', '104th'],
            ['110', '110th'],
            ['111', '111th'], // Special case: ends with 11
            ['112', '112th'], // Special case: ends with 12
            ['113', '113th'], // Special case: ends with 13
            ['121', '121st'],
            ['122', '122nd'],
            ['123', '123rd'],
            
            // Very large numbers
            ['1000', '1000th'],
            ['1001', '1001st'],
            ['1002', '1002nd'],
            ['1003', '1003rd'],
            ['1011', '1011th'], // Ends with 11
            ['1012', '1012th'], // Ends with 12
            ['1013', '1013th'], // Ends with 13
            ['1021', '1021st'],
            ['1022', '1022nd'],
            ['1023', '1023rd'],
            
            // Edge cases from Rust tests
            ['12000', '12000th'],
            ['12001', '12001st'],
            ['12002', '12002nd'],
            ['12003', '12003rd'],
            ['12004', '12004th'],
            
            // Zero
            ['0', '0th'],
            
            // Negative numbers
            ['-1', '-1st'],
            ['-2', '-2nd'],
            ['-3', '-3rd'],
            ['-11', '-11th'],
            ['-21', '-21st'],
        ];
    }
    
    /**
     * @dataProvider ordinalizationProvider
     */
    public function testOrdinalization(string $number, string $expectedOrdinal): void
    {
        $actualOrdinal = Inflector::ordinalize($number);
        $this->assertSame($expectedOrdinal, $actualOrdinal, "Ordinalization failed for: $number");
        
        // Test round-trip: ordinal -> deordinalize should return original number
        $backToNumber = Inflector::deordinalize($actualOrdinal);
        $this->assertSame($number, $backToNumber, "Round-trip failed for: $number -> $actualOrdinal -> $backToNumber");
    }

    // ========== DEORDINALIZATION TESTS ==========
    
    /**
     * Data provider for deordinalization tests
     */
    public static function deordinalizationProvider(): array
    {
        return [
            ['1st', '1'],
            ['2nd', '2'],
            ['3rd', '3'],
            ['4th', '4'],
            ['11th', '11'],
            ['12th', '12'],
            ['13th', '13'],
            ['21st', '21'],
            ['22nd', '22'],
            ['23rd', '23'],
            ['101st', '101'],
            ['102nd', '102'],
            ['103rd', '103'],
            ['111th', '111'],
            ['121st', '121'],
            ['1001st', '1001'],
            ['0th', '0'],
            ['-1st', '-1'],
            ['-21st', '-21'],
        ];
    }
    
    /**
     * @dataProvider deordinalizationProvider
     */
    public function testDeordinalization(string $ordinal, string $expectedNumber): void
    {
        $this->assertSame($expectedNumber, Inflector::deordinalize($ordinal), "Deordinalization failed for: $ordinal");
    }

    // ========== EDGE CASES AND SPECIAL CONDITIONS ==========
    
    public function testDecimalNumbers(): void
    {
        // Decimal numbers should not be ordinalized (based on Rust implementation)
        $this->assertSame('0.1', Inflector::ordinalize('0.1'));
        $this->assertSame('1.5', Inflector::ordinalize('1.5'));
        $this->assertSame('3.14', Inflector::ordinalize('3.14'));
    }
    
    public function testNonNumericStrings(): void
    {
        // Non-numeric strings should return as-is (based on Rust implementation)
        $this->assertSame('a', Inflector::ordinalize('a'));
        $this->assertSame('abc', Inflector::ordinalize('abc'));
        $this->assertSame('test123', Inflector::ordinalize('test123'));
    }
    
    public function testEmptyString(): void
    {
        $this->assertSame('', Inflector::ordinalize(''));
        $this->assertSame('', Inflector::deordinalize(''));
    }
    
    public function testAlreadyOrdinalized(): void
    {
        // Already ordinalized strings should return as-is
        $this->assertSame('1st', Inflector::ordinalize('1st'));
        $this->assertSame('2nd', Inflector::ordinalize('2nd'));
        $this->assertSame('3rd', Inflector::ordinalize('3rd'));
        $this->assertSame('11th', Inflector::ordinalize('11th'));
    }
    
    public function testDeordinalizeNonOrdinal(): void
    {
        // Non-ordinal strings should return as-is after removing any ordinal suffixes
        $this->assertSame('test', Inflector::deordinalize('test'));
        $this->assertSame('123test', Inflector::deordinalize('123test'));
        $this->assertSame('abc', Inflector::deordinalize('abcth')); // removes 'th' suffix
    }

    // ========== PERFORMANCE AND BOUNDARY TESTS ==========
    
    public function testLargeNumbers(): void
    {
        // Test very large numbers
        $this->assertSame('999999th', Inflector::ordinalize('999999'));
        $this->assertSame('1000001st', Inflector::ordinalize('1000001'));
        $this->assertSame('1000002nd', Inflector::ordinalize('1000002'));
        $this->assertSame('1000003rd', Inflector::ordinalize('1000003'));
        $this->assertSame('1000011th', Inflector::ordinalize('1000011'));
    }
    
    public function testSpecialTeenCases(): void
    {
        // Focus on the special teen cases (11, 12, 13) in various contexts
        $teenCases = [
            ['11', '11th'],
            ['12', '12th'],
            ['13', '13th'],
            ['111', '111th'],
            ['112', '112th'],
            ['113', '113th'],
            ['211', '211th'],
            ['212', '212th'],
            ['213', '213th'],
            ['1011', '1011th'],
            ['1012', '1012th'],
            ['1013', '1013th'],
        ];
        
        foreach ($teenCases as [$number, $expected]) {
            $this->assertSame($expected, Inflector::ordinalize($number), "Teen case failed for: $number");
        }
    }
    
    /**
     * Test that the ordinal suffix logic matches Rust implementation exactly
     */
    public function testOrdinalSuffixLogic(): void
    {
        // Test the core logic: numbers ending in 1, 2, 3 get st, nd, rd
        // EXCEPT when they end in 11, 12, 13 which get th
        
        // Numbers ending in 1 (but not 11)
        $onesNotElevens = ['1', '21', '31', '41', '51', '61', '71', '81', '91', '101', '121', '131'];
        foreach ($onesNotElevens as $number) {
            $this->assertStringEndsWith('st', Inflector::ordinalize($number), "Number $number should end with 'st'");
        }
        
        // Numbers ending in 2 (but not 12)
        $twosNotTwelves = ['2', '22', '32', '42', '52', '62', '72', '82', '92', '102', '122', '132'];
        foreach ($twosNotTwelves as $number) {
            $this->assertStringEndsWith('nd', Inflector::ordinalize($number), "Number $number should end with 'nd'");
        }
        
        // Numbers ending in 3 (but not 13)
        $threesNotThirteens = ['3', '23', '33', '43', '53', '63', '73', '83', '93', '103', '123', '133'];
        foreach ($threesNotThirteens as $number) {
            $this->assertStringEndsWith('rd', Inflector::ordinalize($number), "Number $number should end with 'rd'");
        }
        
        // Numbers ending in 11, 12, 13 should get 'th'
        $teenSpecialCases = ['11', '12', '13', '111', '112', '113', '211', '212', '213', '1011', '1012', '1013'];
        foreach ($teenSpecialCases as $number) {
            $this->assertStringEndsWith('th', Inflector::ordinalize($number), "Number $number should end with 'th'");
        }
        
        // All other numbers should get 'th'
        $otherNumbers = ['4', '5', '6', '7', '8', '9', '10', '14', '15', '16', '17', '18', '19', '20', '24', '25'];
        foreach ($otherNumbers as $number) {
            $this->assertStringEndsWith('th', Inflector::ordinalize($number), "Number $number should end with 'th'");
        }
    }
}