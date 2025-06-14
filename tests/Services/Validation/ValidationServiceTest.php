<?php

use Base\Services\Validation\ValidationService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ValidationServiceTest extends TestCase
{
    private const REQUIRED_MESSAGE = 'Величина не должна быть пустой.';
    private const EMAIL_MESSAGE = 'Адрес электронной почты "%s" считается не действительным.';
    private const ALPHANUMERIC_MESSAGE = 'Величина должна состоять из латинских букв и цифр.';
    private const BEETWEEN_MESSAGE = 'Величина должна состоять из 5 - 10 символов.';
    private const SECURE_MESSAGE = 'Пароль должен состоять из 8 - 64 символов и содержать как минимум одну цифру, одну заглавную латинскую букву, одну строчную латинскую букву и один специальный символ.';
    private const SAME_MESSAGE = 'Подтверждение не совпадает с паролем.';
    private const NONEXISTENT_MESSAGE = 'Какое-то сообщение';

    public static function requiredRuleProvider(): array
    {
        // $field, $expectedErrors
        return [
            ['', [self::REQUIRED_MESSAGE]],
            ['aa', []],
        ];
    }
    
    #[DataProvider('requiredRuleProvider')]
    public function test_check_required_rule(string $field, array $expectedErrors): void
    {
        $arrMessage = ['required' => self::REQUIRED_MESSAGE];
        $rule = 'required';
        
        $errors = (new ValidationService())->validate($field, $rule, $arrMessage);
        $this->assertEquals($expectedErrors, $errors);
    }

    public static function emailRuleProvider(): array
    {
        // $field, $expectedErrors
        return [
            // Ошибка не обнаружена, потому что задана пустая строка ''.
            // Предполагается, что всегда используется связка required | email
            ['', []],
            [' ', [sprintf(self::EMAIL_MESSAGE, ' ')]],
            ['aa', [sprintf(self::EMAIL_MESSAGE, 'aa')]],
            ['aa@bb.x', []],
        ];
    }
    
    #[DataProvider('emailRuleProvider')]
    public function test_check_email_rule(string $field, array $expectedErrors): void
    {
        $arrMessage = ['email' => self::EMAIL_MESSAGE];
        $rule = 'email';
        
        $errors = (new ValidationService())->validate($field, $rule, $arrMessage);
        $this->assertEquals($expectedErrors, $errors);
    }

    public static function requiredAndEmailRuleProvider(): array
    {
        // $field, $expectedErrors
        return [
            ['', [self::REQUIRED_MESSAGE]],
            [' ', [sprintf(self::EMAIL_MESSAGE, ' ')]],
            ['aa', [sprintf(self::EMAIL_MESSAGE, 'aa')]],
            ['aa@bb.x', []],
        ];
    }
    
    #[DataProvider('requiredAndEmailRuleProvider')]
    public function test_check_required_and_email_rule(string $field, array $expectedErrors): void
    {
        $arrMessage = ['email' => self::EMAIL_MESSAGE, 'required' => self::REQUIRED_MESSAGE];
        $rule = 'required | email';
        
        $errors = (new ValidationService())->validate($field, $rule, $arrMessage);
        $this->assertEquals($expectedErrors, $errors);
    }

    public static function alphanumericRuleProvider(): array
    {
        // $field, $expectedErrors
        return [
            // Ошибка не обнаружена, потому что задана пустая строка ''.
            // Предполагается, что всегда используется связка required | alphanumeric
            ['', []],
            ['  ', [self::ALPHANUMERIC_MESSAGE]],
            ['<a>', [self::ALPHANUMERIC_MESSAGE]],
            ['aa', []],
        ];
    }
    
    #[DataProvider('alphanumericRuleProvider')]
    public function test_check_alphanumeric_rule(string $field, array $expectedErrors): void
    {
        $arrMessage = ['alphanumeric' => self::ALPHANUMERIC_MESSAGE];
        $rule = 'alphanumeric';
        
        $errors = (new ValidationService())->validate($field, $rule, $arrMessage);
        $this->assertEquals($expectedErrors, $errors);
    }

    public static function requiredAndAlphanumericRuleProvider(): array
    {
        // $field, $expectedErrors
        return [
            ['', [self::REQUIRED_MESSAGE]],
            ['<a>', [self::ALPHANUMERIC_MESSAGE]],
            ['aa1', []],
        ];
    }
    
    #[DataProvider('requiredAndAlphanumericRuleProvider')]
    public function test_check_required_and_alphanumeric_rule(string $field, array $expectedErrors): void
    {
        $arrMessage = ['alphanumeric' => self::ALPHANUMERIC_MESSAGE, 'required' => self::REQUIRED_MESSAGE];
        $rule = 'required | alphanumeric';
        
        $errors = (new ValidationService())->validate($field, $rule, $arrMessage);
        $this->assertEquals($expectedErrors, $errors);
    }

    public static function betweenRuleProvider(): array
    {
        // $field, $expectedErrors
        return [
            // Ошибка не обнаружена, потому что задана пустая строка ''.
            // Предполагается, что всегда используется связка required | between: 5,10
            ['', []],
            // Меньше 5
            ['<a>0', [self::BEETWEEN_MESSAGE]],
            ['<a>01', []],
            ['<a>0123456', []],
            // Больше 10
            ['<a>01234567', [self::BEETWEEN_MESSAGE]],
        ];
    }
    
    #[DataProvider('betweenRuleProvider')]
    public function test_check_between_rule(string $field, array $expectedErrors): void
    {
        $arrMessage = ['between' => self::BEETWEEN_MESSAGE];
        $rule = 'between: 5,10';
        
        $errors = (new ValidationService())->validate($field, $rule, $arrMessage);
        $this->assertEquals($expectedErrors, $errors);
    }

    public static function requiredAndBetweenRuleProvider(): array
    {
        // $field, $expectedErrors
        return [
            ['', [self::REQUIRED_MESSAGE]],
            // Меньше 5
            ['<a>0', [self::BEETWEEN_MESSAGE]],
            ['<a>01', []],
            ['<a>0123456', []],
            // Больше 10
            ['<a>01234567', [self::BEETWEEN_MESSAGE]],
        ];
    }
    
    #[DataProvider('requiredAndBetweenRuleProvider')]
    public function test_check_required_and_between_rule(string $field, array $expectedErrors): void
    {
        $arrMessage = ['between' => self::BEETWEEN_MESSAGE, 'required' => self::REQUIRED_MESSAGE];
        $rule = 'between: 5,10|required';
        
        $errors = (new ValidationService())->validate($field, $rule, $arrMessage);
        $this->assertEquals($expectedErrors, $errors);
    }

    public static function secureRuleProvider(): array
    {
        $long = '$fF'.'0123456789'.'0123456789'.'0123456789'.'0123456789'.'0123456789'.'0123456789'.'a#';
        
        // $field, $expectedErrors
        return [
            // Ошибка не обнаружена, потому что задана пустая строка ''.
            // Предполагается, что всегда используется связка required | secure
            ['', []],
            ['  ', [self::SECURE_MESSAGE]],
            // Нет цифры
            ['$abcdefG', [self::SECURE_MESSAGE]],
            // Нет заглавной латинской буквы
            ['$f123456', [self::SECURE_MESSAGE]],
            // Нет строчной латинской буквы
            ['$F123456', [self::SECURE_MESSAGE]],
            // Нет специального символа
            ['fF123456', [self::SECURE_MESSAGE]],
            // Присутствует кириллица
            ['$fF1234Ы', [self::SECURE_MESSAGE]],
            // Мало символов (всего 7)
            ['$fF1234', [self::SECURE_MESSAGE]],
            // Много символов (всего 65)
            [$long, [self::SECURE_MESSAGE]],
            // Правильная строка
            ['$fF12345', []],
        ];
    }
    
    #[DataProvider('secureRuleProvider')]
    public function test_check_secure_rule(string $field, array $expectedErrors): void
    {
        $arrMessage = ['secure' => self::SECURE_MESSAGE];
        $rule = 'secure';
        
        $errors = (new ValidationService())->validate($field, $rule, $arrMessage);
        $this->assertEquals($expectedErrors, $errors);
    }

    public static function sameRuleProvider(): array
    {
        // $field, $other, $expectedErrors
        return [
            // Ошибка не обнаружена, потому что пароль и подтверждение - пустые строки ''.
            // Предполагается, что всегда используется связка required | same
            ['', '', []],
            // Возникает ошибка, потому что величина $field не изменяется, а разбиение правила сопровождается удалением пробелов по краям
            // (сравниваются строки ' ' и '').
            [' ', ' ', [self::SAME_MESSAGE]],
            // (сравниваются строки '' и '').
            ['', ' ', []],
            [' ', '', [self::SAME_MESSAGE]],
            ['1', '', [self::SAME_MESSAGE]],
            ['', '2', [self::SAME_MESSAGE]],
            ['1', '2', [self::SAME_MESSAGE]],
            ['1', '1', []],
        ];
    }
    
    #[DataProvider('sameRuleProvider')]
    public function test_check_same_rule(string $field, string $other, array $expectedErrors): void
    {
        $arrMessage = ['same' => self::SAME_MESSAGE];
        $rule = 'same: %s';
        
        $errors = (new ValidationService())->validate($field, sprintf($rule, $other), $arrMessage);
        $this->assertEquals($expectedErrors, $errors);
    }

    public static function sameAlphanumericRuleProvider(): array
    {
        // $field, $other, $expectedErrors
        return [
            ['test', 'test', []],
            ['test', 'notest', [self::SAME_MESSAGE]],
            ['test$', 'test$', [self::ALPHANUMERIC_MESSAGE]],
            ['test$', 'test', [self::SAME_MESSAGE, self::ALPHANUMERIC_MESSAGE]],
        ];
    }
    
    #[DataProvider('sameAlphanumericRuleProvider')]
    public function test_check_same_and_alphanumeric_rule(string $field, string $other, array $expectedErrors): void
    {
        $arrMessage = ['same' => self::SAME_MESSAGE, 'alphanumeric' => self::ALPHANUMERIC_MESSAGE];
        $rule = 'same: %s|alphanumeric';
        
        $errors = (new ValidationService())->validate($field, sprintf($rule, $other), $arrMessage);
        $this->assertEqualsCanonicalizing($expectedErrors, $errors);
    }
    
    public static function nonexistentRuleProvider(): array
    {
        // $rule, $ruleName
        return [
            ['', ''],
            ['nonexistent', 'nonexistent'],
            ['nonexistent:value', 'nonexistent'],
            ['nonexistent:n1,n2', 'nonexistent'],
        ];
    }
    
    #[DataProvider('nonexistentRuleProvider')]
    public function test_nonexistent_rule(string $rule, string $ruleName): void
    {
        $arrMessage = ['nonexistent' => self::NONEXISTENT_MESSAGE];
        
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Задано не существующее правило $ruleName");
        
        (new ValidationService())->validate('test', $rule, $arrMessage);
    }
    
    public static function forRuleNoMessageProvider(): array
    {
        // $rule, $ruleName
        return [
            ['secure | required', 'secure'],
            ['secure', 'secure'],
            ['between: 5,10', 'between'],
            ['same: x', 'same'],
        ];
    }
    
    #[DataProvider('forRuleNoMessageProvider')]
    public function test_for_rule_no_message(string $rule, string $ruleName): void
    {
        $arrMessage = ['fail' => self::SECURE_MESSAGE, 'required' => self::REQUIRED_MESSAGE];
        
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Для правила $ruleName не задано сообщение");
        
        (new ValidationService())->validate('test', $rule, $arrMessage);
    }
}
