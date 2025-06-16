<?php

namespace Base\Session;

final class ErrorsSession extends BaseSession
{
    protected static ErrorsSession|null $instance = null;
    
    public function getErrors(): array
    {
        $errors = $_SESSION['errors'] ?? [];
        $this->destroy();
        
        return $errors;
    }
    
    public function setErrorMessage(string $key, string $message): void
    {
        $_SESSION['errors'][$key] = $message;
    }
    
    public function destroy(): void
    {
        unset($_SESSION['errors']);
    }
    
    public function isEmpty(): bool
    {
        return !count($_SESSION['errors'] ?? []);
    }
}
