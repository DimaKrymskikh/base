<?php

use Base\Exceptions\RuleException;
use Base\ValueObjects\User\PasswordValue;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PasswordValueTest extends TestCase
{
    private const PASSWORD_REQUIRED_MESSAGE = 'Пароль не должен быть пустым.';
    private const SECURE_MESSAGE = 'Пароль должен состоять из 8 - 64 символов и содержать как минимум одну цифру, одну заглавную латинскую букву, одну строчную латинскую букву и один специальный символ.';
    private const SAME_MESSAGE = 'Подтверждение не совпадает с паролем.';

    public static function successProvider(): array
    {
        // $password, $verification
        return [
            ['12345678Dima$', '12345678Dima$'],
            ['12345 Dima', '12345 Dima'],
        ];
    }
    
    #[DataProvider('successProvider')]
    public function test_correct_passwords(string $password, string $verification): void
    {
        $this->assertEquals($password, PasswordValue::create($password, $verification)->value);
    }
    
    public static function failProvider(): array
    {
        $longPassword = '0123456789'.'abcdefghij'.'0123456789'.'abcdefghij'.'0123456789'.'abcdefghij'.'abcde';

        // $password, $verification, $expectedErrors
        return [
            [null, null, [self::PASSWORD_REQUIRED_MESSAGE]],
            // Короткий пароль
            ['1234567', '1234567', [self::SECURE_MESSAGE]],
            [$longPassword, $longPassword, [self::SECURE_MESSAGE]],
            // Подтверждение не совпадает с паролем
            ['Dima$1234', 'Dima$12345', [self::SAME_MESSAGE]],
            // Обработка правила same:%s сопровождается удалением пробелов на концах (см. Base\Services\Validation\ValidationService::validate).
            // Пароль будет сравниваться с подтверждением, у которого удалили пробелы на концах.
            ['Dima$1234 ', 'Dima$1234 ', [self::SAME_MESSAGE]],
            // Присутствует кириллица
            ['Dima$1234ы', 'Dima$1234ы', [self::SECURE_MESSAGE]],
        ];
    }
    
    #[DataProvider('failProvider')]
    public function test_noncorrect_passwords(string|null $password, string|null $verification, array $expectedErrors): void
    {
        $this->expectException(RuleException::class);
        $this->expectExceptionMessage(implode(' ', $expectedErrors));
        
        PasswordValue::create($password, $verification);
    }
}
