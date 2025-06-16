<?php

namespace Base\Session;

final class FlashMessagesSession extends BaseSession
{
    private const FLASH_MESSAGE = 'flash_message';

    protected static FlashMessagesSession|null $instance = null;
    
    public function setFlashMessage(string $name, string $message): void
    {
        $_SESSION[self::FLASH_MESSAGE][$name] = $message;
    }
    
    public function getFlashMessage(string $name): string
    {
        $flashMessage = $_SESSION[self::FLASH_MESSAGE][$name] ?? '';
        unset($_SESSION[self::FLASH_MESSAGE][$name]);
        
        return $flashMessage;
    }
    
    public function destroy(): void
    {
        unset($_SESSION[self::FLASH_MESSAGE]);
    }
}
