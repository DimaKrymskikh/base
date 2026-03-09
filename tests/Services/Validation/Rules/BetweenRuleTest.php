<?php

use Base\Services\Validation\Rules\BetweenRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class BetweenRuleTest extends TestCase
{
    public static function successProvider(): array
    {
        return [
            ['<a>01', 5, 10], // 5 символов
            ['<a>0 ', 5, 10], // 5 символов
            ['abcde01234', 5, 10], // 10 символов
        ];
    }
    
    #[DataProvider('successProvider')]
    public function test_success_check(string $field, int $min, int $max): void
    {
        $this->assertTrue(BetweenRule::check($field, $min, $max));
    }
    
    public static function failProvider(): array
    {
        return [
            ['abcd', 5, 10],
            ['abcde012345', 5, 10],  // 11 символов
        ];
    }
    
    #[DataProvider('failProvider')]
    public function test_fail_check(string $field, int $min, int $max): void
    {
        $this->assertFalse(BetweenRule::check($field, $min, $max));
    }
}
