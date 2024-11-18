<?php

namespace Base\Services\FlashMessages;

final class FlashMessagesService implements FlashMessagesServiceInterface
{
    private const FLASH_MESSAGE = 'flash_message';

    private static ?FlashMessagesService $instance = null;
    
    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    private function __construct()
    {}
    
    public function createFlashMessage(string $name, string $message): void
    {
        $_SESSION[self::FLASH_MESSAGE][$name] = $message;
    }
    
    public function getFlashMessage(string $name): string
    {
        if (!isset($_SESSION[self::FLASH_MESSAGE][$name])) {
            return '';
        }

        $flashMessage = $_SESSION[self::FLASH_MESSAGE][$name];

        unset($_SESSION[self::FLASH_MESSAGE][$name]);
        
        return $flashMessage;
    }
    
    public function destroy(): void
    {
        unset($_SESSION[self::FLASH_MESSAGE]);
    }
}
