<?php

use Base\Services\Validation\Rules\RequiredRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class RequiredRuleTest extends TestCase
{
    public static function successProvider(): array
    {
        return [
            ['aa'],
            [' aa@bb.x '],
            [' '],
        ];
    }
    
    #[DataProvider('successProvider')]
    public function test_success_check(string $field): void
    {
        $this->assertTrue(RequiredRule::check($field));
    }
    
    public static function failProvider(): array
    {
        return [
            [''],
        ];
    }
    
    #[DataProvider('failProvider')]
    public function test_fail_check(string $field): void
    {
        $this->assertFalse(RequiredRule::check($field));
    }
}
