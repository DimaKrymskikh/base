<?php

use Base\Session\ErrorsSession;
use Base\ValueObjects\User\LoginValue;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class LoginValueTest extends TestCase
{
    private const REQUIRED_MESSAGE = 'Логин не должен быть пустым.';
    private const ALPHANUMERIC_MESSAGE = 'Логин должен состоять из латинских букв и цифр.';
    private const BEETWEEN_MESSAGE = 'Логин должен состоять из 4 - 18 символов.';
    
    private ErrorsSession $errorsSession;

    public static function correctLoginsProvider(): array
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
    
    #[DataProvider('correctLoginsProvider')]
    public function test_correct_logins(string $login, string $input): void
    {
        $this->assertEquals($login, LoginValue::create($input)->value);
        $this->assertEquals([], $this->errorsSession->getErrors());
    }
    
    public static function nonCorrectLoginsProvider(): array
    {
        // $login, $input, $expectedErrors
        return [
            ['', null, [self::REQUIRED_MESSAGE]],
            ['', '', [self::REQUIRED_MESSAGE]],
            ['', ' ', [self::REQUIRED_MESSAGE]],
            ['Bo$', 'Bo$', [self::BEETWEEN_MESSAGE, self::ALPHANUMERIC_MESSAGE]],
            ['Boo0123456789abcdef', 'Boo0123456789abcdef', [self::BEETWEEN_MESSAGE]],
        ];
    }
    
    #[DataProvider('nonCorrectLoginsProvider')]
    public function test_noncorrect_logins(string $login, string|null $input, array $expectedErrors): void
    {
        $this->assertEquals($login, LoginValue::create($input)->value);
        
        foreach ($expectedErrors as $error) {
            $this->assertStringContainsString($error, $this->errorsSession->getErrors()['login']);
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
