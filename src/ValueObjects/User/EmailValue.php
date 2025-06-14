<?php

namespace Base\ValueObjects\User;

use Base\Services\Validation\ValidationService;
use Base\Session\ErrorsSession;

readonly class EmailValue
{
    public string $value;
    
    private const OPTIONS = 'required | email';
    
    private const RULE_MESSAGES = [
        'required' => 'Адрес электронной почты не должен быть пустым.',
        'email' => 'Адрес электронной почты "%s" считается не действительным.'
    ];

    private function __construct(string|null $email)
    {
        $emailTrim = mb_trim($email ?? '');
        $errors = (new ValidationService())->validate($emailTrim, self::OPTIONS, self::RULE_MESSAGES);
        
        $this->value = $emailTrim;
        
        if (count($errors)) {
            ErrorsSession::getInstance()->setErrorMessage('email', implode(' ', $errors));
        }
    }
    
    public static function create(string|null $email): self
    {
        return new self($email);
    }
}
