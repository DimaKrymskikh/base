<?php

use Base\Session\ErrorsSession;
use Base\ValueObjects\User\EmailValue;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class EmailValueTest extends TestCase
{
    private const REQUIRED_MESSAGE = 'Адрес электронной почты не должен быть пустым.';
    private const EMAIL_MESSAGE = 'Адрес электронной почты "%s" считается не действительным.';
    
    private ErrorsSession $errorsSession;

    public static function correctEmailsProvider(): array
    {
        // $email, $input
        return [
            ['aa@b.com', 'aa@b.com'],
            ['xxx@yyyyy.z', ' xxx@yyyyy.z '],
            ['x@b.ru', "  \t    x@b.ru \n\r\t\v\x00"],
        ];
    }
    
    #[DataProvider('correctEmailsProvider')]
    public function test_correct_emails(string $email, string $input): void
    {
        $this->assertEquals($email, EmailValue::create($input)->value);
        $this->assertEquals([], $this->errorsSession->getErrors());
    }
    
    public static function nonCorrectEmailsProvider(): array
    {
        // $email, $input, $expectedErrors
        return [
            ['', null, [self::REQUIRED_MESSAGE]],
            ['', '', [self::REQUIRED_MESSAGE]],
            ['', ' ', [self::REQUIRED_MESSAGE]],
            ['0', " 0\n\r\t\v\x00\0", [sprintf(self::EMAIL_MESSAGE, '0')]],
            ['aab.com', 'aab.com', [sprintf(self::EMAIL_MESSAGE, 'aab.com')]],
            ['xxx@yyyyyz', 'xxx@yyyyyz ', [sprintf(self::EMAIL_MESSAGE, 'xxx@yyyyyz')]],
        ];
    }
    
    #[DataProvider('nonCorrectEmailsProvider')]
    public function test_noncorrect_emails(string $email, string|null $input, array $expectedErrors): void
    {
        $this->assertEquals($email, EmailValue::create($input)->value);
        
        foreach ($expectedErrors as $error) {
            $this->assertStringContainsString($error, $this->errorsSession->getErrors()['email']);
        }
    }

    protected function setUp(): void
    {
        $this->errorsSession = ErrorsSession::getInstance();
        $this->errorsSession->destroy();
    }
    
    protected function tearDown(): void
    {
        unset($_SESSION['errors']);
    }
}
