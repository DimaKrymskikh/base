<?php

use Base\Services\Validation\Rules\EmailRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class EmailRuleTest extends TestCase
{
    public static function successProvider(): array
    {
        return [
            ['aa@bb.x'],
        ];
    }
    
    #[DataProvider('successProvider')]
    public function test_success_check(string $field): void
    {
        $this->assertTrue(EmailRule::check($field));
    }
    
    public static function failProvider(): array
    {
        return [
            ['aa.x'],
            [' aa@bb.x '],
        ];
    }
    
    #[DataProvider('failProvider')]
    public function test_fail_check(string $field): void
    {
        $this->assertFalse(EmailRule::check($field));
    }
}
