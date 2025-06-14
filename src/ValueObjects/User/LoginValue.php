<?php

namespace Base\ValueObjects\User;

use Base\Session\ErrorsSession;
use Base\Services\Validation\ValidationService;

readonly class LoginValue
{
    public string $value;
    
    private const OPTIONS = 'required | alphanumeric | between: 4, 18';
    
    private const RULE_MESSAGES = [
        'required' => 'Логин не должен быть пустым.',
        'alphanumeric' => 'Логин должен состоять из латинских букв и цифр.',
        'between' => "Логин должен состоять из 4 - 18 символов."
    ];
    
    private function __construct(string|null $login)
    {
        $loginTrim = mb_trim($login ?? '');
        $errors = (new ValidationService())->validate($loginTrim, self::OPTIONS, self::RULE_MESSAGES);
        
        $this->value = $loginTrim;
        
        if (count($errors)) {
            ErrorsSession::getInstance()->setErrorMessage('login', implode(' ', $errors));
        }
    }
    
    public static function create(string|null $login): self
    {
        return new self($login);
    }
}
