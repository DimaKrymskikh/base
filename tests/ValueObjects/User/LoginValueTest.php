<?php

use Base\Exceptions\RuleException;
use Base\ValueObjects\User\LoginValue;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class LoginValueTest extends TestCase
{
    private const REQUIRED_MESSAGE = 'Логин не должен быть пустым.';
    private const ALPHANUMERIC_MESSAGE = 'Логин должен состоять из латинских букв и цифр.';
    private const BEETWEEN_MESSAGE = 'Логин должен состоять из 4 - 18 символов.';

    public static function successProvider(): array
    {
        // $login, $input
        return [
            ['Dima', 'Dima'],
            ['Dima24', 'Dima24'],
            ['dima24', '   dima24 '],
            ['TEST', 'TEST'],
            ['BooBar', 'BooBar'],
        ];
    }
    
    #[DataProvider('successProvider')]
    public function test_correct_logins(string $login, string $input): void
    {
        $this->assertEquals($login, LoginValue::create($input)->value);
    }
    
    public static function failProvider(): array
    {
        // $input, $expectedErrors
        return [
            [null, [self::REQUIRED_MESSAGE]],
            ['', [self::REQUIRED_MESSAGE]],
            [' ', [self::REQUIRED_MESSAGE]],
            ['Bo$', [self::ALPHANUMERIC_MESSAGE, self::BEETWEEN_MESSAGE]],
            ['Boo0123456789abcdef', [self::BEETWEEN_MESSAGE]],
        ];
    }
    
    #[DataProvider('failProvider')]
    public function test_noncorrect_logins(string|null $input, array $expectedErrors): void
    {
        $this->expectException(RuleException::class);
        $this->expectExceptionMessage(implode(' ', $expectedErrors));
        
        LoginValue::create($input);
    }
}
