<?php

use Base\Services\Validation\Rules\SecureRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class SecureRuleTest extends TestCase
{
    public static function successProvider(): array
    {
        return [
            ['$fF12345'],
            ['$$abF12345'],
        ];
    }
    
    #[DataProvider('successProvider')]
    public function test_success_check(string $field): void
    {
        $this->assertTrue(SecureRule::check($field));
    }
    
    public static function failProvider(): array
    {
        $long = '$fF'.'0123456789'.'0123456789'.'0123456789'.'0123456789'.'0123456789'.'0123456789'.'a#';
        
        return [
            // Нет цифры
            ['$abcdefG'],
            // Нет заглавной латинской буквы
            ['$f123456'],
            // Нет строчной латинской буквы
            ['$F123456'],
            // Нет специального символа
            ['fF123456'],
            // Присутствует кириллица
            ['$fF1234Ы'],
            // Мало символов (всего 7)
            ['$fF1234'],
            // Много символов (всего 65)
            [$long],
        ];
    }
    
    #[DataProvider('failProvider')]
    public function test_fail_check(string $field): void
    {
        $this->assertFalse(SecureRule::check($field));
    }
}
