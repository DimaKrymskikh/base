<?php

namespace Base\ValueObjects\User;

use Base\Exceptions\RuleException;
use Base\Services\Validation\ValidationService;

readonly class PasswordValue
{
    public string $value;
    
    private const PASSWORD_OPTIONS = 'required|secure|same:%s';
    
    private const PASSWORD_RULE_MESSAGES = [
        'required' => 'Пароль не должен быть пустым.',
        'secure' => 'Пароль должен состоять из 8 - 64 символов и содержать как минимум одну цифру, одну заглавную латинскую букву, одну строчную латинскую букву и один специальный символ.',
        'same' => 'Подтверждение не совпадает с паролем.'
    ];
    
    private function __construct(string|null $password, string|null $verification)
    {
        $stringPassword = $password ?? '';
        $stringVerification = $verification ?? '';
        
        $validationService = new ValidationService();
        
        $errors = $validationService->validate($stringPassword,  sprintf(self::PASSWORD_OPTIONS, $stringVerification), self::PASSWORD_RULE_MESSAGES);
        
        if (count($errors)) {
            throw new RuleException('password', implode(' ', $errors));
        }
        
        $this->value = $stringPassword;
    }
    
    public static function create(string|null $password, string|null $verification): self
    {
        return new self($password, $verification);
    }
}
