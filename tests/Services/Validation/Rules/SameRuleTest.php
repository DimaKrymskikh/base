<?php

use Base\Services\Validation\Rules\SameRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class SameRuleTest extends TestCase
{
    public static function successProvider(): array
    {
        return [
            ['abc', 'abc'],
            ['12345', '12345'],
        ];
    }
    
    #[DataProvider('successProvider')]
    public function test_success_check(string $field, string $other): void
    {
        $this->assertTrue(SameRule::check($field, $other));
    }
    
    public static function failProvider(): array
    {
        return [
            ['ab', ' ab'],
            ['123a', '123A'],
        ];
    }
    
    #[DataProvider('failProvider')]
    public function test_fail_check(string $field, string $other): void
    {
        $this->assertFalse(SameRule::check($field, $other));
    }
}
