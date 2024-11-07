<?php

use Base\Services\Validation\ValidationService;
use PHPUnit\Framework\TestCase;

class ValidationServiceTest extends TestCase
{
    private const REQUIRED_MESSAGE = 'Величина не должна быть пустой.';
    private const EMAIL_MESSAGE = 'Адрес электронной почты "%s" считается не действительным.';
    private const ALPHANUMERIC_MESSAGE = 'Величина должна состоять из латинских букв и цифр.';
    private const BEETWEEN_MESSAGE = 'Величина должна состоять из 5 - 10 символов.';
    private const SECURE_MESSAGE = 'Пароль должен состоять из 8 - 64 символов и содержать как минимум одну цифру, одну заглавную латинскую букву, одну строчную латинскую букву и один специальный символ.';
    private const SAME_MESSAGE = 'Подтверждение не совпадает с паролем.';

    public function test_check_required_rule(): void
    {
        $arrMessage = ['required' => self::REQUIRED_MESSAGE];
        $rule = 'required';
        
        $errors = (new ValidationService())->validate('', $rule, $arrMessage);
        $this->assertEquals([self::REQUIRED_MESSAGE], $errors);
        
        // При проверке у величины удаляются пробелы по краям, поэтому результат такой же как у пустой строки ''
        $errors2 = (new ValidationService())->validate('  ', $rule, $arrMessage);
        $this->assertEquals([self::REQUIRED_MESSAGE], $errors2);
        
        $errors3 = (new ValidationService())->validate('aa', $rule, $arrMessage);
        $this->assertEquals([], $errors3);
    }

    public function test_check_email_rule(): void
    {
        $arrMessage = ['email' => self::EMAIL_MESSAGE];
        $rule = 'email';
        
        // Ошибка не обнаружена, потому что задана пустая строка ''.
        // Предполагается, что всегда используется связка required | email
        $errors = (new ValidationService())->validate('', $rule, $arrMessage);
        $this->assertEquals([], $errors);
        
        $errors2 = (new ValidationService())->validate('  ', $rule, $arrMessage);
        $this->assertEquals([sprintf(self::EMAIL_MESSAGE, '  ')], $errors2);
        
        $errors3 = (new ValidationService())->validate('aa', $rule, $arrMessage);
        $this->assertEquals([sprintf(self::EMAIL_MESSAGE, 'aa')], $errors3);
        
        $errors4 = (new ValidationService())->validate('aa@bb.x', $rule, $arrMessage);
        $this->assertEquals([], $errors4);
    }

    public function test_check_required_and_email_rule(): void
    {
        $arrMessage = ['email' => self::EMAIL_MESSAGE, 'required' => self::REQUIRED_MESSAGE];
        $rule = 'required | email';
        
        $errors = (new ValidationService())->validate('', $rule, $arrMessage);
        $this->assertEquals([self::REQUIRED_MESSAGE], $errors);
        
        $errors2 = (new ValidationService())->validate('  ', $rule, $arrMessage);
        $this->assertEqualsCanonicalizing([self::REQUIRED_MESSAGE, sprintf(self::EMAIL_MESSAGE, '  ')], $errors2);
        
        $errors3 = (new ValidationService())->validate('aa', $rule, $arrMessage);
        $this->assertEquals([sprintf(self::EMAIL_MESSAGE, 'aa')], $errors3);
        
        $errors4 = (new ValidationService())->validate('aa@bb.x', $rule, $arrMessage);
        $this->assertEquals([], $errors4);
    }

    public function test_check_alphanumeric_rule(): void
    {
        $arrMessage = ['alphanumeric' => self::ALPHANUMERIC_MESSAGE];
        $rule = 'alphanumeric';
        
        // Ошибка не обнаружена, потому что задана пустая строка ''.
        // Предполагается, что всегда используется связка required | alphanumeric
        $errors = (new ValidationService())->validate('', $rule, $arrMessage);
        $this->assertEquals([], $errors);
        
        $errors2 = (new ValidationService())->validate('  ', $rule, $arrMessage);
        $this->assertEquals([self::ALPHANUMERIC_MESSAGE], $errors2);
        
        $errors3 = (new ValidationService())->validate('<a>', $rule, $arrMessage);
        $this->assertEquals([self::ALPHANUMERIC_MESSAGE], $errors3);
        
        $errors4 = (new ValidationService())->validate('aa', $rule, $arrMessage);
        $this->assertEquals([], $errors4);
    }

    public function test_check_required_and_alphanumeric_rule(): void
    {
        $arrMessage = ['alphanumeric' => self::ALPHANUMERIC_MESSAGE, 'required' => self::REQUIRED_MESSAGE];
        $rule = 'required | alphanumeric';
        
        $errors = (new ValidationService())->validate('', $rule, $arrMessage);
        $this->assertEquals([self::REQUIRED_MESSAGE], $errors);
        
        $errors2 = (new ValidationService())->validate('  ', $rule, $arrMessage);
        $this->assertEqualsCanonicalizing([self::REQUIRED_MESSAGE, sprintf(self::ALPHANUMERIC_MESSAGE, '  ')], $errors2);
        
        $errors3 = (new ValidationService())->validate('<a>', $rule, $arrMessage);
        $this->assertEquals([self::ALPHANUMERIC_MESSAGE], $errors3);
        
        $errors4 = (new ValidationService())->validate('aa1', $rule, $arrMessage);
        $this->assertEquals([], $errors4);
    }

    public function test_check_between_rule(): void
    {
        $arrMessage = ['between' => self::BEETWEEN_MESSAGE];
        $rule = 'between: 5,10';
        
        // Ошибка не обнаружена, потому что задана пустая строка ''.
        // Предполагается, что всегда используется связка required | between: 5,10
        $errors = (new ValidationService())->validate('', $rule, $arrMessage);
        $this->assertEquals([], $errors);
        
        $errors2 = (new ValidationService())->validate('  ', $rule, $arrMessage);
        $this->assertEquals([self::BEETWEEN_MESSAGE], $errors2);
        
        $errors3 = (new ValidationService())->validate('<a>0', $rule, $arrMessage);
        $this->assertEquals([self::BEETWEEN_MESSAGE], $errors3);
        
        $errors4 = (new ValidationService())->validate('<a>01', $rule, $arrMessage);
        $this->assertEquals([], $errors4);
        
        $errors5 = (new ValidationService())->validate('<a>0123456', $rule, $arrMessage);
        $this->assertEquals([], $errors5);
        
        $errors6 = (new ValidationService())->validate('<a>01234567', $rule, $arrMessage);
        $this->assertEquals([self::BEETWEEN_MESSAGE], $errors6);
    }

    public function test_check_required_and_between_rule(): void
    {
        $arrMessage = ['between' => self::BEETWEEN_MESSAGE, 'required' => self::REQUIRED_MESSAGE];
        $rule = 'between: 5,10|required';
        
        $errors = (new ValidationService())->validate('', $rule, $arrMessage);
        $this->assertEquals([self::REQUIRED_MESSAGE], $errors);
        
        $errors2 = (new ValidationService())->validate('  ', $rule, $arrMessage);
        $this->assertEqualsCanonicalizing([self::REQUIRED_MESSAGE, self::BEETWEEN_MESSAGE], $errors2);
        
        $errors3 = (new ValidationService())->validate('<a>0', $rule, $arrMessage);
        $this->assertEquals([self::BEETWEEN_MESSAGE], $errors3);
        
        $errors4 = (new ValidationService())->validate('<a>01', $rule, $arrMessage);
        $this->assertEquals([], $errors4);
        
        $errors5 = (new ValidationService())->validate('<a>0123456', $rule, $arrMessage);
        $this->assertEquals([], $errors5);
        
        $errors6 = (new ValidationService())->validate('<a>01234567', $rule, $arrMessage);
        $this->assertEquals([self::BEETWEEN_MESSAGE], $errors6);
    }

    public function test_check_secure_rule(): void
    {
        $arrMessage = ['secure' => self::SECURE_MESSAGE];
        $rule = 'secure';
        
        // Ошибка не обнаружена, потому что задана пустая строка ''.
        // Предполагается, что всегда используется связка required | secure
        $errors = (new ValidationService())->validate('', $rule, $arrMessage);
        $this->assertEquals([], $errors);
        
        $errors2 = (new ValidationService())->validate('  ', $rule, $arrMessage);
        $this->assertEquals([self::SECURE_MESSAGE], $errors2);
        
        // Нет цифры
        $errors3 = (new ValidationService())->validate('$abcdefG', $rule, $arrMessage);
        $this->assertEquals([self::SECURE_MESSAGE], $errors3);
        
        // Нет заглавной латинской буквы
        $errors4 = (new ValidationService())->validate('$f123456', $rule, $arrMessage);
        $this->assertEquals([self::SECURE_MESSAGE], $errors4);
        
        // Нет строчной латинской буквы
        $errors5 = (new ValidationService())->validate('$F123456', $rule, $arrMessage);
        $this->assertEquals([self::SECURE_MESSAGE], $errors5);
        
        // Нет специального символа
        $errors6 = (new ValidationService())->validate('fF123456', $rule, $arrMessage);
        $this->assertEquals([self::SECURE_MESSAGE], $errors6);
        
        // Присутствует кириллица
        $errors7 = (new ValidationService())->validate('$fF1234Ы', $rule, $arrMessage);
        $this->assertEquals([self::SECURE_MESSAGE], $errors7);
        
        // Мало символов (всего 7)
        $errors8 = (new ValidationService())->validate('$fF1234', $rule, $arrMessage);
        $this->assertEquals([self::SECURE_MESSAGE], $errors8);
        
        // Ммного символов (всего 65)
        $errors9 = (new ValidationService())->validate('$fF01234567890123456789012345678901234567890123456789012345678901', $rule, $arrMessage);
        $this->assertEquals([self::SECURE_MESSAGE], $errors9);
        
        // Правильный пароль
        $errors10 = (new ValidationService())->validate('$fF12345', $rule, $arrMessage);
        $this->assertEquals([], $errors10);
    }

    public function test_check_same_rule(): void
    {
        $arrMessage = ['same' => self::SAME_MESSAGE];
        $rule = 'same: %s';
        
        // Ошибка не обнаружена, потому что пароль и подтверждение - пустые строки ''.
        // Предполагается, что всегда используется связка required | same
        $errors = (new ValidationService())->validate('', sprintf($rule, ''), $arrMessage);
        $this->assertEquals([], $errors);
        
        // Возникает ошибка, потому что величина $field не изменяется, а разбиение правила сопровождается удалением пробелов по краям
        // (сравниваются строки '  ' и '').
        $errors2 = (new ValidationService())->validate('  ', sprintf($rule, '  '), $arrMessage);
        $this->assertEquals([self::SAME_MESSAGE], $errors2);
        
        // Пароль не совпадает с подтверждением.
        $errors3 = (new ValidationService())->validate('1', sprintf($rule, '2'), $arrMessage);
        $this->assertEquals([self::SAME_MESSAGE], $errors3);
        
        // Пароль совпадает с подтверждением.
        $errors4 = (new ValidationService())->validate('1', sprintf($rule, '1'), $arrMessage);
        $this->assertEquals([], $errors4);
    }
}
