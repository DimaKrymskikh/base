<?php

use Base\Exceptions\RuleException;
use Base\ValueObjects\User\EmailValue;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class EmailValueTest extends TestCase
{
    private const REQUIRED_MESSAGE = 'Адрес электронной почты не должен быть пустым.';
    private const EMAIL_MESSAGE = 'Адрес электронной почты "%s" считается не действительным.';

    public static function successProvider(): array
    {
        // $email, $input
        return [
            ['aa@b.com', 'aa@b.com'],
            ['xxx@yyyyy.z', ' xxx@yyyyy.z '],
            ['x@b.ru', "  \t    x@b.ru \n\r\t\v\x00"],
        ];
    }
    
    #[DataProvider('successProvider')]
    public function test_correct_emails(string $email, string $input): void
    {
        $this->assertEquals($email, EmailValue::create($input)->value);
    }
    
    public static function failProvider(): array
    {
        // $input, $expectedErrors
        return [
            [null, [self::REQUIRED_MESSAGE]],
            ['', [self::REQUIRED_MESSAGE]],
            [' ', [self::REQUIRED_MESSAGE]],
            [" 0\n\r\t\v\x00\0", [sprintf(self::EMAIL_MESSAGE, '0')]],
            ['aab.com', [sprintf(self::EMAIL_MESSAGE, 'aab.com')]],
            ['xxx@yyyyyz ', [sprintf(self::EMAIL_MESSAGE, 'xxx@yyyyyz')]],
        ];
    }
    
    #[DataProvider('failProvider')]
    public function test_noncorrect_emails(string|null $input, array $expectedErrors): void
    {
        $this->expectException(RuleException::class);
        $this->expectExceptionMessage(implode(' ', $expectedErrors));
        
        EmailValue::create($input);
    }
}
