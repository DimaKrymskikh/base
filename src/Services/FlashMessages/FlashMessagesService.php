<?php

namespace Base\Services\FlashMessages;

final class FlashMessagesService implements FlashMessagesServiceInterface
{
    private const FLASH_MESSAGE = 'flash_message';

    public function createFlashMessage(string $name, string $message): void
    {
        if (isset($_SESSION[self::FLASH_MESSAGE][$name])) {
            unset($_SESSION[self::FLASH_MESSAGE][$name]);
        }
        
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
