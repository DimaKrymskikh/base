<?php

namespace Base\Services\FlashMessages;

interface FlashMessagesServiceInterface 
{
    public function createFlashMessage(string $name, string $message): void;
    
    public function getFlashMessage(string $name): string;
}
