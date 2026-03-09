<?php

namespace Base\Exceptions;

use Base\Session\ErrorsSession;

/**
 * Исключение, которое сохраняет ошибку в сессии.
 * Применяется при валидации данных, введённых пользователем.
 */
final class RuleException extends \Exception implements HtmlExceptionInterface
{
    /**
     * Исключение для валидации данных, введённых пользователем.
     * 
     * @param string $attribute
     * @param string $exceptionMessage
     */
    public function __construct(
            private string $attribute,
            private string $exceptionMessage
    ) {
        parent::__construct($this->exceptionMessage);
    }
    
    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function render(): void
    {
        ErrorsSession::getInstance()->setErrorMessage($this->attribute, $this->exceptionMessage);
    }
}
