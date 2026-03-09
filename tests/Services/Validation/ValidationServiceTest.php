<?php

use Base\Services\Validation\ValidationService;
use PHPUnit\Framework\TestCase;

class ValidationServiceTest extends TestCase
{
    private const REQUIRED_MESSAGE = 'Величина не должна быть пустой.';
    private const EMAIL_MESSAGE = 'Адрес электронной почты "%s" считается не действительным.';
    private const BEETWEEN_MESSAGE = 'Величина должна состоять из %2$s - %3$s символов.';
    private const FAIL_MESSAGE = 'Какое-то сообщение';

    public function test_success_validate_two_simple_rules(): void
    {
        $arrMessage = [
            'required' => self::REQUIRED_MESSAGE,
            'email' => self::EMAIL_MESSAGE
        ];
        
        $errors = (new ValidationService())->validate('aa@bb.x', 'required|email', $arrMessage);
        $this->assertEquals([], $errors);
    }
    
    public function test_success_validate_simple_and_complex_rules(): void
    {
        $arrMessage = [
            'required' => self::REQUIRED_MESSAGE,
            'between' => self::BEETWEEN_MESSAGE
        ];
        
        $errors = (new ValidationService())->validate('aa@bb.x', 'between:5,10|required', $arrMessage);
        $this->assertEquals([], $errors);
    }

    public function test_fail_validate_one_fail_rule_from_two_simple_rules(): void
    {
        $email = 'aa';
        $arrMessage = [
            'required' => self::REQUIRED_MESSAGE,
            'email' => self::EMAIL_MESSAGE
        ];
        
        $errors = (new ValidationService())->validate($email, 'required|email', $arrMessage);
        $this->assertEquals([sprintf(self::EMAIL_MESSAGE, $email)], $errors);
    }
    
    public function test_fail_validate_two_fail_rules_from_two_simple_rules(): void
    {
        $email = '';
        $arrMessage = [
            'required' => self::REQUIRED_MESSAGE,
            'email' => self::EMAIL_MESSAGE
        ];
        
        $errors = (new ValidationService())->validate($email, 'required|email', $arrMessage);
        $this->assertEquals([
            self::REQUIRED_MESSAGE,
            sprintf(self::EMAIL_MESSAGE, $email)
        ], $errors);
    }
    
    public function test_fail_validate_one_fail_complex_rule_from_two_rules(): void
    {
        $field = 'abcd';
        $arrMessage = [
            'required' => self::REQUIRED_MESSAGE,
            'between' => self::BEETWEEN_MESSAGE
        ];
        
        $errors = (new ValidationService())->validate($field, 'required|between:5,10', $arrMessage);
        $this->assertEquals([sprintf(self::BEETWEEN_MESSAGE, $field, '5', '10')], $errors);
    }
    
    public function test_fail(): void
    {
        $field = '';
        $arrMessage = [
            'required' => self::REQUIRED_MESSAGE,
            'between' => self::BEETWEEN_MESSAGE
        ];
        
        $errors = (new ValidationService())->validate($field, 'between:5,10|required', $arrMessage);
        $this->assertEqualsCanonicalizing([
            self::REQUIRED_MESSAGE,
            sprintf(self::BEETWEEN_MESSAGE, $field, '5', '10')
        ], $errors);
    }
    
/*****************************************************************************************************************************
 * Серия тестов с исключениями.
 *****************************************************************************************************************************/    
    public function test_fail_validate_fail_rule(): void
    {
        $rule = $options = 'fail';
        $arrMessage = [$rule => self::FAIL_MESSAGE];
        
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Задано не существующее правило $rule");
        
        (new ValidationService())->validate('test', $options, $arrMessage);
    }

    public function test_fail_validate_fail_rule_with_parameters(): void
    {
        $options = 'fail:7';
        $arrMessage = [
            'fail' => self::FAIL_MESSAGE,
        ];
        
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Задано не существующее правило fail");
        
        (new ValidationService())->validate('test', $options, $arrMessage);
    }

    public function test_fail_validate_fail_message(): void
    {
        $rule = $options = 'required';
        $arrMessage = ['fail' => self::FAIL_MESSAGE];
        
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Для правила $rule не задано сообщение");
        
        (new ValidationService())->validate('test', $options, $arrMessage);
    }
    
    public function test_fail_validate_fail_message_with_parameters(): void
    {
        $options = 'between:5,10';
        $arrMessage = [
            'fail' => self::FAIL_MESSAGE,
        ];
        
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Для правила between не задано сообщение");
        
        (new ValidationService())->validate('test', $options, $arrMessage);
    }
}
