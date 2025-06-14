<?php

namespace Base\ValueObjects\User;

use Base\Session\ErrorsSession;
use Base\Services\Validation\ValidationService;

readonly class PasswordValue
{
    public string $value;
    
    private const PASSWORD_OPTIONS = 'required | secure';
    private const VERIFICATION_OPTIONS = 'required | same:%s';
    
    private const PASSWORD_RULE_MESSAGES = [
        'required' => 'Пароль не должен быть пустым.',
        'secure' => 'Пароль должен состоять из 8 - 64 символов и содержать как минимум одну цифру, одну заглавную латинскую букву, одну строчную латинскую букву и один специальный символ.'
    ];
    
    private const VERIFICATION_RULE_MESSAGES = [
        'required' => 'Подтверждение не должно быть пустым.',
        'same' => 'Подтверждение не совпадает с паролем.'
    ];
    
    private function __construct(string|null $password, string|null $verification)
    {
        $stringPassword = $password ?? '';
        $stringVerification = $verification ?? '';
        
        $validationService = new ValidationService();
        
        $errorsPassword = $validationService->validate($stringPassword, self::PASSWORD_OPTIONS, self::PASSWORD_RULE_MESSAGES);
        $errorsVerification = $validationService->validate($stringVerification, sprintf(self::VERIFICATION_OPTIONS, $stringPassword), self::VERIFICATION_RULE_MESSAGES);
        $errors = array_merge($errorsPassword, $errorsVerification);
        
        $this->value = count($errors) ? '' : $stringPassword;
        
        if (count($errorsPassword)) {
            ErrorsSession::getInstance()->setErrorMessage('password', implode(' ', $errors));
        }
        
        if (count($errorsVerification)) {
            ErrorsSession::getInstance()->setErrorMessage('verification', implode(' ', $errors));
        }
    }
    
    public static function create(string|null $password, string|null $verification): self
    {
        return new self($password, $verification);
    }
}
