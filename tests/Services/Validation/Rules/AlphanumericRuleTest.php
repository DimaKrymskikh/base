<?php

use Base\Services\Validation\Rules\AlphanumericRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class AlphanumericRuleTest extends TestCase
{
    public static function successProvider(): array
    {
        return [
            ['aa'],
            ['777'],
            ['aa777'],
        ];
    }
    
    #[DataProvider('successProvider')]
    public function test_success_check(string $field): void
    {
        $this->assertTrue(AlphanumericRule::check($field));
    }
    
    public static function failProvider(): array
    {
        return [
            ['ыыы'],
            ['<div>'],
            ['aa '],
        ];
    }
    
    #[DataProvider('failProvider')]
    public function test_fail_check(string $field): void
    {
        $this->assertFalse(AlphanumericRule::check($field));
    }
}
